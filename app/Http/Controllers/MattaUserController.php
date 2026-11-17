<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\KaryawanAnak;
use App\Models\KaryawanPendidikan;
use App\Models\KaryawanPelatihan;
use App\Models\KaryawanSertifikasi;
use App\Models\Attendance;
use App\Models\Overtime;
use App\Models\TrxCuti;
use App\Models\TrxIjin;
use App\Models\TrxSakit;
use App\Models\TrxReimburseMaster;
use App\Models\TrxPenilaian;
use App\Models\TrxNonaktifMaster;
use App\Models\TrxRekomendasiMaster;
use App\Models\TrxPayroll;
use App\Models\AbsensiKesehatan;
use App\Models\Feeds;
use App\Http\Controllers\Api\MattaApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MattaUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Build rich user context for the logged-in user.
     */
    public function getUserContext(): array
    {
        $user = auth()->user();
        $nik = $user->nik ?? '';
        $karyawan = Karyawan::with([
            '_perusahaan', '_divisi', '_departemen', '_subdepartemen',
            '_jabatan', '_level', '_wg', '_status', '_agama', '_kelamin',
            '_warga', '_kawin', '_layer1'
        ])->where('nik', $nik)->first();

        if (!$karyawan && !empty($user->email)) {
            $karyawan = Karyawan::with([
                '_perusahaan', '_divisi', '_departemen', '_subdepartemen',
                '_jabatan', '_level', '_wg', '_status', '_agama', '_kelamin',
                '_warga', '_kawin', '_layer1'
            ])->where('email', $user->email)->first();
        }

        $name = $karyawan->nama ?? $user->name ?? ($user ? 'User ' . $user->id : 'M. Daffa Dzaky Pratama');
        $nip = $karyawan->nik ?? ($nik ?: ($user ? 'USR-' . $user->id : 'P0826155'));
        $words = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if ($w !== '' && preg_match('/^[A-Za-z]/', $w)) {
                $initials .= strtoupper($w[0]);
                if (strlen($initials) >= 2) break;
            }
        }
        if ($initials === '') {
            $initials = 'MT';
        }

        $company = $karyawan?->_perusahaan?->nama ?? 'PT Alita Praya Mitra';
        $division = $karyawan?->_divisi?->nama ?? 'Engineering & Operations';
        $department = $karyawan?->_departemen?->nama ?? 'IT Workplace';
        $subDepartment = $karyawan?->_subdepartemen?->nama ?? 'Application Development';
        $role = $karyawan?->_jabatan?->jabatan ?? 'Staff / Specialist';
        $level = $karyawan?->_level?->level ?? 'Staff';
        $wgName = $karyawan?->_wg?->wg_name ?? 'Reguler - 0830-1730';
        $approverName = $karyawan?->_layer1?->nama ?? 'Muhammad Rizky';
        $approverNip = $karyawan?->_layer1?->nik ?? '01031502441';
        $statusKaryawan = $karyawan?->_status?->status_karyawan ?? 'Karyawan Tetap';

        return [
            'id' => $karyawan->id ?? $user->id ?? 1,
            'name' => $name,
            'nip' => $nip,
            'initials' => $initials,
            'email' => $karyawan->email ?? $user->email ?? 'user@alita.id',
            'role' => $role,
            'level' => $level,
            'status_karyawan' => $statusKaryawan,
            'company' => $company,
            'directorate' => $division,
            'division' => $division,
            'department' => $department,
            'sub_department' => $subDepartment,
            'unit' => $company . ' — ' . $division . ' / ' . $department,
            'short_unit' => $department,
            'wg' => $wgName,
            'approver' => $approverName,
            'approver_nip' => $approverNip,
            'karyawan' => $karyawan,
        ];
    }

    /**
     * Get today's Clock-In and Clock-Out timestamps from trx_tbl_attendance.
     */
    public function getTodayAttendanceState(string $nik): array
    {
        $today = date('Y-m-d');
        $rows = Attendance::where('att_nik', $nik)
            ->where('att_date', $today)
            ->orderBy('id', 'asc')
            ->get();

        $inRow = $rows->first(function ($r) {
            return in_array(strtolower($r->att_type ?? ''), ['clock-in', 'check-in']);
        });
        $outRow = $rows->reverse()->first(function ($r) {
            return in_array(strtolower($r->att_type ?? ''), ['clock-out', 'check-out']);
        });

        $inMs = $inRow ? (Carbon::parse($inRow->att_timestamp)->timestamp * 1000) : null;
        $outMs = ($outRow && $inRow) ? (Carbon::parse($outRow->att_timestamp)->timestamp * 1000) : null;

        return [
            'in' => $inMs,
            'out' => $outMs,
            'early' => false,
            'inLat' => $inRow->att_lat ?? '-6.2088',
            'inLng' => $inRow->att_long ?? '106.8227',
            'inAddress' => $inRow->att_address ?? 'Kantor Pusat (HQ), Jakarta',
            'outLat' => $outRow->att_lat ?? '-6.2088',
            'outLng' => $outRow->att_long ?? '106.8227',
            'outAddress' => $outRow->att_address ?? 'Kantor Pusat (HQ), Jakarta',
        ];
    }

    /**
     * Handle AJAX Clock-In / Clock-Out from MATTA Dashboard.
     * Saves directly into trx_tbl_attendance via MattaApi@store so it appears in both User and Admin Attendance Logs.
     */
    public function clock(Request $request)
    {
        $u = $this->getUserContext();
        $nik = $u['nip'];
        $attType = $request->input('att_type', 'Clock-in');
        if (strtolower($attType) === 'clock-in') {
            $attType = 'Clock-in';
        } elseif (strtolower($attType) === 'clock-out') {
            $attType = 'Clock-out';
        }

        $clockinLimit = date('Y-m-d') . ' ' . (optional(optional($u['karyawan'])->_wg)->clockin ?? '08:30:00');
        $attStatus = 'Book-in';
        if ($attType === 'Clock-in') {
            $attStatus = Carbon::now()->gt(Carbon::parse($clockinLimit)) ? 'Late-in' : 'Book-in';
        } elseif ($attType === 'Clock-out') {
            $attStatus = 'Book-out';
        }

        $mattaApi = new MattaApi();
        $apiReq = new Request([
            'att_nik' => $nik,
            'att_type' => $attType,
            'att_data_src' => 'Regular',
            'att_wd_ot_id' => 0,
            'att_status' => $attStatus,
            'att_long' => $request->input('att_long') ?: '106.8227',
            'att_lat' => $request->input('att_lat') ?: '-6.2088',
            'att_address' => $request->input('att_address') ?: 'Kantor Pusat (HQ), Jakarta',
        ]);

        $response = $mattaApi->store($apiReq);
        $resData = json_decode($response->getContent(), true);

        return response()->json([
            'ok' => true,
            'success' => $resData['success'] ?? true,
            'message' => $resData['message'] ?? 'Presensi berhasil disimpan.',
            'state' => $this->getTodayAttendanceState($nik),
        ]);
    }

    /**
     * Reset today's attendance for testing when user clicks Reset Presensi.
     */
    public function resetClock(Request $request)
    {
        $u = $this->getUserContext();
        Attendance::where('att_nik', $u['nip'])
            ->where('att_date', date('Y-m-d'))
            ->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Build the 8 modules configuration + rows for Dashboard and Module pages.
     */
    public function getModulesData(array $u): array
    {
        $nik = $u['nip'];
        $name = $u['name'];
        $role = $u['role'];
        $dept = $u['department'];
        $unit = $u['unit'];

        $isDemoUser = ($nik === 'P0826155') || str_contains(strtolower($name), 'daffa');

        // Jika bukan demo user (setiap beda user / role lain), data aktivitas dimulai dari 0 dan memuat pengajuan riil milik user bersangkutan
        if (!$isDemoUser) {
            $otRows = [];
            $evRows = [];
            $lvRows = [];
            $pmRows = [];
            $exRows = [];
            $rmRows = [];
            $skRows = [];
            $cfRows = [];

            try {
                // Reimbursement / Pengembalian riil milik user
                $dbRm = TrxReimburseMaster::where('nik_karyawan', $nik)->orderBy('id', 'desc')->get();
                foreach ($dbRm as $r) {
                    $stName = optional($r->_status)->status ?? 'Waiting Approval';
                    if (str_contains(strtolower($stName), 'setuju') || str_contains(strtolower($stName), 'berhasil')) {
                        $st = 'Approved';
                    } elseif (str_contains(strtolower($stName), 'tolak')) {
                        $st = 'Rejected';
                    } else {
                        $st = 'Waiting Approval';
                    }
                    $createdTime = \Carbon\Carbon::parse($r->created_at);
                    $rmRows[] = [
                        'id' => '#RMB-' . $r->periode . '-' . str_pad($r->id, 4, '0', STR_PAD_LEFT),
                        'form' => 'Klaim Pengembalian (Reimbursement)',
                        'file' => '-',
                        'name' => $name,
                        'nip' => $nik,
                        'role' => $role,
                        'unit' => $dept ?: $unit,
                        'date' => $createdTime->format('d M Y'),
                        'time' => $createdTime->format('H:i') . ' WIB (Rp ' . number_format($r->klaim_kesehatan, 0, ',', '.') . ')',
                        'status' => $st,
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('getModulesData load rows error: ' . $e->getMessage());
            }
        } else {
            // Demo user (M. Daffa Dzaky Pratama - P0826155) memiliki data aktivitas sesuai desain
            $otRows = [
                ['id' => '#OVT-2026-0052', 'form' => 'Formulir Lembur', 'file' => 'overtime_daffa.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure & Security', 'date' => '15 Okt 2026', 'time' => '18:00 - 21:30 WIB (3.5 Jam)', 'status' => 'Waiting Approval'],
                ['id' => '#OVT-2026-0047', 'form' => 'Formulir Lembur', 'file' => 'overtime_0047.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Product Design', 'date' => '12 Okt 2026', 'time' => '18:00 - 20:00 WIB (2.0 Jam)', 'status' => 'Approved'],
                ['id' => '#OVT-2026-0041', 'form' => 'Formulir Lembur', 'file' => 'overtime_0041.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Engineering / IT', 'date' => '08 Okt 2026', 'time' => '20:00 - 00:00 WIB (4.0 Jam)', 'status' => 'Approved'],
                ['id' => '#OVT-2026-0036', 'form' => 'Formulir Lembur', 'file' => 'overtime_0036.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure & Security', 'date' => '03 Okt 2026', 'time' => '18:00 - 21:00 WIB (3.0 Jam)', 'status' => 'Approved'],
                ['id' => '#OVT-2026-0029', 'form' => 'Formulir Lembur', 'file' => 'overtime_0029.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure & Security', 'date' => '26 Sep 2026', 'time' => '18:30 - 21:30 WIB (3.0 Jam)', 'status' => 'Approved'],
            ];

            $evRows = [
                ['id' => '#EVL-2026-0003', 'form' => 'Evaluasi Kinerja Q3', 'file' => 'evaluasi_q3_daffa.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '30 Sep 2026', 'time' => '14:00 WIB', 'status' => 'Approved'],
                ['id' => '#EVL-2026-0001', 'form' => 'Evaluasi Mid-Year KPI', 'file' => 'evaluasi_mid_daffa.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '30 Jun 2026', 'time' => '10:00 WIB', 'status' => 'Approved'],
            ];

            $lvRows = [
                ['id' => '#LVE-2026-0031', 'form' => 'Cuti Tahunan (3 Hari)', 'file' => 'cuti_tahunan.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '01 Okt 2026', 'time' => '08:12 WIB', 'status' => 'Approved'],
                ['id' => '#LVE-2026-0022', 'form' => 'Cuti Bersama Hari Raya', 'file' => 'cuti_bersama.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '18 Agu 2026', 'time' => '09:40 WIB', 'status' => 'Approved'],
                ['id' => '#LVE-2026-0015', 'form' => 'Cuti Khusus Penting', 'file' => 'cuti_khusus.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '10 Mei 2026', 'time' => '08:30 WIB', 'status' => 'Approved'],
            ];

            $pmRows = [
                ['id' => '#PRM-2026-0025', 'form' => 'Izin Pulang Awal', 'file' => 'izin_keluarga.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '20 Okt 2026', 'time' => '13:00 - 15:00 WIB', 'status' => 'Waiting Approval'],
                ['id' => '#PRM-2026-0021', 'form' => 'Izin Datang Terlambat', 'file' => 'bukti_terlambat.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '14 Okt 2026', 'time' => '09:00 - 11:30 WIB', 'status' => 'Approved'],
                ['id' => '#PRM-2026-0018', 'form' => 'Izin Urusan Perbankan', 'file' => 'permit_bank.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '24 Sep 2026', 'time' => '13:00 - 14:30 WIB', 'status' => 'Approved'],
                ['id' => '#PRM-2026-0014', 'form' => 'Izin Keperluan Pribadi', 'file' => 'permit_daffa.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '10 Sep 2026', 'time' => '10:00 - 12:00 WIB', 'status' => 'Approved'],
                ['id' => '#PRM-2026-0008', 'form' => 'Izin Check-up Medis', 'file' => 'permit_medis.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '15 Agu 2026', 'time' => '14:00 - 16:00 WIB', 'status' => 'Approved'],
            ];

            $exRows = [
                ['id' => '#RSG-2026-0019', 'form' => 'Serah Terima Perangkat IT', 'file' => 'handover_laptop.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'IT Operation', 'date' => '18 Okt 2026', 'time' => '10:00 WIB', 'status' => 'Waiting Approval'],
                ['id' => '#RSG-2026-0015', 'form' => 'Pengembalian ID Card & Akses', 'file' => 'form_idcard.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'General Affairs', 'date' => '12 Okt 2026', 'time' => '14:30 WIB', 'status' => 'Approved'],
                ['id' => '#RSG-2026-0011', 'form' => 'Klirens Beban Perpustakaan', 'file' => 'bebas_pustaka.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'HC Operation', 'date' => '06 Okt 2026', 'time' => '09:15 WIB', 'status' => 'Approved'],
                ['id' => '#RSG-2026-0007', 'form' => 'Serah Terima Repositori Kode', 'file' => 'repo_handover.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Engineering', 'date' => '28 Sep 2026', 'time' => '16:00 WIB', 'status' => 'Approved'],
                ['id' => '#RSG-2026-0002', 'form' => 'Klirens Kasbon & Keuangan', 'file' => 'klirens_kas.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Finance', 'date' => '15 Sep 2026', 'time' => '11:20 WIB', 'status' => 'Approved'],
            ];

            $rmRows = [
                ['id' => '#RMB-2026-0009', 'form' => 'Klaim Transportasi Klien', 'file' => 'transport_klien.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '28 Sep 2026', 'time' => '14:05 WIB (Rp 185.000)', 'status' => 'Waiting Approval'],
                ['id' => '#RMB-2026-0006', 'form' => 'Klaim Resep Obat Apotek', 'file' => 'kwitansi_apotek.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '02 Sep 2026', 'time' => '10:20 WIB (Rp 320.000)', 'status' => 'Approved'],
                ['id' => '#RMB-2026-0002', 'form' => 'Klaim Kacamata Tahunan', 'file' => 'kwitansi_optik.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '15 Jul 2026', 'time' => '11:00 WIB (Rp 750.000)', 'status' => 'Approved'],
            ];

            $skRows = [
                ['id' => '#SCK-2026-0014', 'form' => 'Surat Keterangan Sakit (MC)', 'file' => 'mc_siloam_daffa.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '05 Okt 2026', 'time' => '08:30 WIB', 'status' => 'Approved'],
                ['id' => '#SCK-2026-0008', 'form' => 'Surat Istirahat Rawat Jalan', 'file' => 'mc_klinik_daffa.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '12 Agu 2026', 'time' => '09:00 WIB', 'status' => 'Approved'],
            ];

            $cfRows = [
                ['id' => '#CNF-2026-0033', 'form' => 'Konfirmasi Datang Terlambat', 'file' => 'bukti_presensi.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '22 Okt 2026', 'time' => '08:35 WIB', 'status' => 'Waiting Approval'],
                ['id' => '#CNF-2026-0028', 'form' => 'Konfirmasi Lupa Clock Out', 'file' => 'cnf_clockout.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '16 Okt 2026', 'time' => '17:45 WIB', 'status' => 'Approved'],
                ['id' => '#CNF-2026-0022', 'form' => 'Konfirmasi Kendala Mesin FP', 'file' => 'cnf_fp_hq.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '09 Okt 2026', 'time' => '08:40 WIB', 'status' => 'Approved'],
                ['id' => '#CNF-2026-0017', 'form' => 'Konfirmasi Lembur Lapangan', 'file' => 'cnf_lapangan.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '02 Okt 2026', 'time' => '18:10 WIB', 'status' => 'Approved'],
                ['id' => '#CNF-2026-0010', 'form' => 'Konfirmasi Meeting Klien Eksternal', 'file' => 'cnf_meeting.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '25 Sep 2026', 'time' => '08:30 WIB', 'status' => 'Approved'],
                ['id' => '#CNF-2026-0004', 'form' => 'Konfirmasi Dinas Luar Kota', 'file' => 'cnf_dinas.pdf', 'name' => 'M. Daffa Dzaky', 'nip' => 'P0826155', 'role' => 'Frontend Developer', 'unit' => 'Infrastructure', 'date' => '12 Sep 2026', 'time' => '17:35 WIB', 'status' => 'Approved'],
            ];
        }

        return [
            'overtime' => [
                'name' => 'Overtime',
                'short' => 'Lembur',
                'list' => 'Daftar Lembur',
                'desc' => 'Riwayat & status pengajuan lembur karyawan beserta durasi dan tugas lembur',
                'apply' => 'Ajukan Lembur',
                'href' => route('matta.form', 'overtime'),
                'prefix' => 'OVT',
                'formName' => 'Formulir Lembur',
                'rows' => $otRows,
            ],
            'evaluation' => [
                'name' => 'Evaluation',
                'short' => 'Evaluasi',
                'list' => 'Daftar Evaluasi',
                'desc' => 'Riwayat & status pengajuan evaluasi kinerja dan KPI berkala karyawan',
                'apply' => 'Ajukan Evaluasi',
                'href' => route('matta.form', 'evaluation'),
                'prefix' => 'EVL',
                'formName' => 'Formulir Evaluasi Kinerja',
                'rows' => $evRows,
            ],
            'leave' => [
                'name' => 'Leave',
                'short' => 'Cuti',
                'list' => 'Daftar Cuti',
                'desc' => 'Riwayat & status pengajuan cuti tahunan, cuti bersama, dan cuti khusus karyawan',
                'apply' => 'Ajukan Cuti',
                'href' => route('matta.form', 'leave'),
                'prefix' => 'LVE',
                'formName' => 'Formulir Cuti Tahunan',
                'rows' => $lvRows,
            ],
            'permit' => [
                'name' => 'Permit',
                'short' => 'Izin',
                'list' => 'Daftar Izin',
                'desc' => 'Riwayat & status pengajuan izin terlambat, pulang awal, dan keluar kantor sementara',
                'apply' => 'Ajukan Izin',
                'href' => route('matta.form', 'permit'),
                'prefix' => 'PRM',
                'formName' => 'Formulir Izin Keperluan Pribadi',
                'rows' => $pmRows,
            ],
            'exit-clearance' => [
                'name' => 'Exit Clearance',
                'short' => 'Izin Keluar',
                'list' => 'Daftar Izin Keluar',
                'desc' => 'Riwayat & status proses serah terima aset, klirens departemen, dan exit clearance',
                'apply' => 'Ajukan Izin Keluar',
                'href' => route('matta.form', 'exit-clearance'),
                'prefix' => 'RSG',
                'formName' => 'Formulir Izin Keluar',
                'rows' => $exRows,
            ],
            'reimbursement' => [
                'name' => 'Reimbursement',
                'short' => 'Pengembalian',
                'list' => 'Daftar Pengembalian',
                'desc' => 'Riwayat & status pengajuan klaim biaya transportasi, medis, dan operasional kerja',
                'apply' => 'Ajukan Pengembalian',
                'href' => route('matta.form', 'reimbursement'),
                'prefix' => 'RMB',
                'formName' => 'Formulir Pengembalian',
                'rows' => $rmRows,
            ],
            'sick-leave' => [
                'name' => 'Sick Leave',
                'short' => 'Izin Sakit',
                'list' => 'Daftar Izin Sakit',
                'desc' => 'Riwayat & status pengajuan izin sakit karyawan beserta surat keterangan dokter (MC)',
                'apply' => 'Ajukan Izin Sakit',
                'href' => route('matta.form', 'sick-leave'),
                'prefix' => 'SCK',
                'formName' => 'Surat Keterangan Sakit',
                'rows' => $skRows,
            ],
            'confirmation' => [
                'name' => 'Confirmation',
                'short' => 'Konfirmasi',
                'list' => 'Daftar Konfirmasi',
                'desc' => 'Riwayat & status konfirmasi kehadiran, penyesuaian jam kerja, dan dinas luar karyawan',
                'apply' => 'Ajukan Konfirmasi',
                'href' => route('matta.form', 'confirmation'),
                'prefix' => 'CNF',
                'formName' => 'Formulir Konfirmasi Kehadiran',
                'rows' => $cfRows,
            ],
        ];
    }

    /**
     * Dashboard (Home) page.
     */
    public function dashboard()
    {
        $u = $this->getUserContext();
        $todayAtt = $this->getTodayAttendanceState($u['nip']);
        $modules = $this->getModulesData($u);

        $userNik = $u['nip'];
        $authNik = auth()->user()->nik ?? '';
        $niks = array_unique(array_filter([$userNik, $authNik, 'bacadc621', 'P0826155']));

        $attendedDates = Attendance::whereIn('att_nik', $niks)
            ->whereIn(DB::raw('LOWER(att_type)'), ['clock-in', 'check-in'])
            ->distinct()
            ->pluck('att_date')
            ->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })
            ->values()
            ->toArray();

        if (!empty($todayAtt['in']) && !in_array(date('Y-m-d'), $attendedDates)) {
            $attendedDates[] = date('Y-m-d');
        }

        $leaveDates = TrxCuti::whereIn('nik_karyawan', $niks)
            ->where('status_cuti', '>=', 2)
            ->pluck('tanggal_mulai')
            ->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })
            ->values()
            ->toArray();

        $sickDates = TrxSakit::whereIn('nik_karyawan', $niks)
            ->where('status_sakit', '>=', 2)
            ->pluck('tanggal_mulai')
            ->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })
            ->values()
            ->toArray();

        return view('matta.dashboard', compact('u', 'todayAtt', 'modules', 'attendedDates', 'leaveDates', 'sickDates'));
    }

    /**
     * Build rich feeds dataset combining DB feeds and MATTA illustrated articles.
     */
    public function getFeedsCatalog(): array
    {
        return [
            [
                'slug' => 'happy-new-year',
                'img' => 'happy-new-year',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Happy New Year',
                'status' => 'active',
                'date' => '02 Januari 2026',
                'stamp' => '02-01-2026 08:00:00',
                'author' => 'Corporate Communication',
                'caption' => 'Perayaan pergantian tahun — dokumentasi internal',
                'excerpt' => 'Tahun baru selalu membawa lembar yang masih bersih — kesempatan untuk menata ulang prioritas, merayakan capaian bersama, dan melangkah dengan semangat yang sama.',
                'lead' => 'Tahun baru selalu membawa <em>lembar yang masih bersih</em> — kesempatan untuk menata ulang prioritas, merayakan capaian bersama, dan melangkah dengan semangat yang sama.',
                'sections' => [
                    ['id' => 'refleksi', 'h' => 'Refleksi tahun lalu', 'paras' => ['Sepanjang tahun lalu, setiap tim bekerja keras menjaga layanan tetap berjalan sambil terus memperbaiki cara kerja. Banyak proses yang sebelumnya manual kini sudah berpindah ke sistem, dan kolaborasi antar divisi terasa semakin cair.', 'Terima kasih untuk setiap jam lembur, setiap ide perbaikan, dan setiap rekan yang saling membantu di saat paling sibuk.']],
                    ['id' => 'jadwal', 'h' => 'Jadwal kerja awal tahun', 'paras' => ['Kegiatan operasional kembali normal mulai hari kerja pertama di bulan Januari. Mohon pastikan presensi dilakukan seperti biasa melalui menu Clock In di halaman Home.', 'Bagi tim yang masih menjalani cuti, pastikan pengajuan sudah disetujui atasan di menu Forms → Leave agar tidak tercatat sebagai ketidakhadiran.']],
                    ['id' => 'harapan', 'h' => 'Harapan untuk tahun ini', 'paras' => ['Tahun ini kita akan fokus pada tiga hal: layanan yang lebih cepat, data yang lebih rapi, dan lingkungan kerja yang lebih sehat. Semua dimulai dari kebiasaan kecil setiap hari.', 'Selamat tahun baru. Mari jadikan tahun ini tahun yang lebih baik — untuk diri sendiri, tim, dan perusahaan.']],
                ],
                'set' => 'v1',
            ],
            [
                'slug' => 'pengumuman-penting',
                'img' => 'pengumuman-penting',
                'tag' => 'PENGUMUMAN',
                'tagLabel' => 'Pengumuman',
                'title' => 'Pengumuman Penting',
                'status' => 'active',
                'date' => '05 Mei 2026',
                'stamp' => '05-05-2026 17:32:40',
                'author' => 'Human Capital',
                'caption' => 'Pemberitahuan resmi manajemen',
                'excerpt' => 'Pemberitahuan resmi terkait penyesuaian jadwal operasional dan pedoman kerja yang berlaku bagi seluruh karyawan mulai bulan ini.',
                'lead' => 'Pemberitahuan resmi terkait <em>penyesuaian jadwal operasional</em> dan pedoman kerja yang berlaku bagi seluruh karyawan mulai bulan ini.',
                'sections' => [
                    ['id' => 'perubahan', 'h' => 'Apa yang berubah', 'paras' => ['Jam kerja reguler tetap 08:30 – 17:30 WIB. Yang berubah adalah batas toleransi keterlambatan dan alur pengajuan izin, yang kini seluruhnya dilakukan melalui MATTA.', 'Pengajuan izin, cuti, dan lembur yang dikirim di luar sistem tidak lagi dapat diproses oleh tim Human Capital.']],
                    ['id' => 'terdampak', 'h' => 'Siapa yang terdampak', 'paras' => ['Ketentuan ini berlaku untuk seluruh karyawan tetap, kontrak, dan peserta magang di semua unit kerja. Tim dengan jadwal shift akan menerima jadwal tersendiri dari atasan masing-masing.']],
                    ['id' => 'langkah', 'h' => 'Langkah yang perlu dilakukan', 'paras' => ['Periksa kembali data profil Anda di menu My Profile, terutama nomor telepon dan atasan langsung (approver). Data yang salah dapat membuat pengajuan Anda tertahan.', 'Jika ada pertanyaan, hubungi tim Human Capital melalui email hr@alita.id.']],
                ],
                'set' => 'both',
                'cat' => 'PENTING',
            ],
            [
                'slug' => 'libur-nasional',
                'img' => 'libur-nasional',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Libur Nasional',
                'status' => 'active',
                'date' => '12 Agustus 2026',
                'stamp' => '12-08-2026 09:15:00',
                'author' => 'Human Capital',
                'caption' => 'Peta Indonesia — ilustrasi hari libur nasional',
                'excerpt' => 'Menjelang hari libur nasional, berikut jadwal operasional kantor dan hal-hal yang perlu diperhatikan sebelum libur dimulai.',
                'lead' => 'Menjelang <em>hari libur nasional</em>, berikut jadwal operasional kantor dan hal-hal yang perlu diperhatikan sebelum libur dimulai.',
                'sections' => [
                    ['id' => 'jadwal-libur', 'h' => 'Jadwal libur', 'paras' => ['Kantor tutup pada tanggal merah sesuai kalender pemerintah. Kegiatan operasional kembali normal pada hari kerja berikutnya.', 'Presensi tidak perlu dilakukan pada hari libur, dan hari tersebut tidak akan dihitung sebagai ketidakhadiran.']],
                    ['id' => 'layanan', 'h' => 'Layanan yang tetap berjalan', 'paras' => ['Tim IT Infrastructure dan layanan piket tetap bersiaga untuk menangani gangguan kritis. Jadwal piket dibagikan langsung oleh masing-masing atasan.']],
                    ['id' => 'cuti', 'h' => 'Pengajuan cuti di sekitar hari libur', 'paras' => ['Jika ingin mengambil cuti sebelum atau sesudah hari libur, ajukan paling lambat tiga hari kerja sebelumnya melalui menu Forms → Leave agar atasan sempat mengatur pembagian tugas.']],
                ],
                'set' => 'v1',
            ],
            [
                'slug' => 'batik-nasional',
                'img' => 'batik-nasional',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Batik Nasional',
                'status' => 'active',
                'date' => '01 Oktober 2026',
                'stamp' => '01-10-2026 10:00:00',
                'author' => 'Corporate Communication',
                'caption' => 'Proses membatik dengan canting',
                'excerpt' => 'Setiap tanggal 2 Oktober kita memperingati Hari Batik Nasional — warisan yang dibuat dengan sabar, satu titik malam demi satu titik malam.',
                'lead' => 'Setiap tanggal 2 Oktober kita memperingati Hari Batik Nasional — warisan yang <em>dibuat dengan sabar</em>, satu titik malam demi satu titik malam.',
                'sections' => [
                    ['id' => 'pakaian', 'h' => 'Ketentuan berpakaian', 'paras' => ['Seluruh karyawan diimbau mengenakan pakaian batik pada hari peringatan. Bagi yang bekerja dari rumah, batik tetap dianjurkan saat mengikuti rapat daring.']],
                    ['id' => 'kegiatan', 'h' => 'Kegiatan di kantor', 'paras' => ['Akan diadakan sesi foto bersama di lobi dan pemilihan busana batik favorit. Pemenang akan diumumkan melalui kanal Feeds ini.', 'Jadwal kegiatan dapat berubah menyesuaikan agenda rapat masing-masing divisi.']],
                    ['id' => 'motif', 'h' => 'Mengenal motif', 'paras' => ['Setiap daerah memiliki motif dengan makna sendiri. Mari gunakan momen ini untuk saling bercerita tentang batik dari kampung halaman masing-masing.']],
                ],
                'set' => 'v1',
            ],
            [
                'slug' => 'maulid-nabi',
                'img' => 'maulid-nabi',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Maulid Nabi',
                'status' => 'active',
                'date' => '20 Agustus 2026',
                'stamp' => '20-08-2026 08:30:00',
                'author' => 'Human Capital',
                'caption' => 'Kebersamaan dalam peringatan hari besar',
                'excerpt' => 'Dalam rangka memperingati Maulid Nabi Muhammad SAW, berikut informasi jadwal kerja dan kegiatan yang akan berlangsung di lingkungan kantor.',
                'lead' => 'Dalam rangka memperingati Maulid Nabi Muhammad SAW, berikut <em>informasi jadwal kerja</em> dan kegiatan yang akan berlangsung di lingkungan kantor.',
                'sections' => [
                    ['id' => 'jadwal-kerja', 'h' => 'Libur dan jadwal kerja', 'paras' => ['Kantor libur sesuai kalender nasional. Kegiatan operasional kembali normal pada hari kerja berikutnya.']],
                    ['id' => 'peringatan', 'h' => 'Kegiatan peringatan', 'paras' => ['Peringatan sederhana akan diadakan di musala kantor. Karyawan yang ingin ikut berpartisipasi dapat mendaftar melalui panitia kegiatan.', 'Kegiatan bersifat sukarela dan tidak mengganggu jam kerja.']],
                    ['id' => 'operasional', 'h' => 'Catatan untuk tim operasional', 'paras' => ['Tim dengan layanan 24 jam diharapkan berkoordinasi dengan atasan agar pembagian piket tetap adil dan layanan tidak terganggu.']],
                ],
                'set' => 'v1',
            ],
            [
                'slug' => 'ulang-tahun-alita',
                'img' => 'ulang-tahun-alita',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Ulang Tahun Alita',
                'status' => 'active',
                'date' => '15 September 2026',
                'stamp' => '15-09-2026 13:00:00',
                'author' => 'Corporate Communication',
                'caption' => 'Perayaan ulang tahun perusahaan',
                'excerpt' => 'Satu tahun lagi kita lalui bersama. Ulang tahun Alita adalah perayaan untuk semua orang yang membuatnya terus tumbuh.',
                'lead' => 'Satu tahun lagi kita lalui bersama. Ulang tahun Alita adalah <em>perayaan untuk semua orang</em> yang membuatnya terus tumbuh.',
                'sections' => [
                    ['id' => 'perjalanan', 'h' => 'Perjalanan bersama', 'paras' => ['Dari tim kecil hingga hari ini, setiap langkah perusahaan dibangun oleh karyawan yang mau belajar, beradaptasi, dan saling mendukung.']],
                    ['id' => 'acara', 'h' => 'Rangkaian acara', 'paras' => ['Perayaan akan diisi dengan doa bersama, pemotongan kue, dan penghargaan untuk karyawan dengan masa kerja terlama. Detail waktu dan tempat dikirim melalui email undangan.', 'Karyawan di kantor cabang dapat mengikuti acara secara daring.']],
                    ['id' => 'terima-kasih', 'h' => 'Ucapan terima kasih', 'paras' => ['Terima kasih untuk dedikasi dan kerja keras seluruh karyawan. Selamat ulang tahun, Alita.']],
                ],
                'set' => 'v1',
            ],
            [
                'slug' => 'maintenance-sistem-hris',
                'img' => 'pengumuman-penting',
                'tag' => 'PENGUMUMAN',
                'tagLabel' => 'Pengumuman',
                'title' => 'Maintenance Sistem HRIS',
                'status' => 'inactive',
                'date' => '10 Maret 2026',
                'stamp' => '10-03-2026 16:00:00',
                'author' => 'IT Workplace',
                'caption' => 'Pemberitahuan pemeliharaan sistem',
                'excerpt' => 'Sistem HRIS akan menjalani pemeliharaan terjadwal untuk peningkatan performa dan keamanan.',
                'lead' => 'Sistem HRIS akan menjalani <em>pemeliharaan terjadwal</em> untuk peningkatan performa dan keamanan.',
                'sections' => [
                    ['id' => 'waktu', 'h' => 'Waktu pemeliharaan', 'paras' => ['Pemeliharaan dilakukan di luar jam kerja agar tidak mengganggu aktivitas harian.']],
                    ['id' => 'terdampak-hris', 'h' => 'Layanan yang terdampak', 'paras' => ['Selama pemeliharaan, menu presensi, pengajuan, dan slip gaji tidak dapat diakses sementara.']],
                    ['id' => 'setelah', 'h' => 'Setelah pemeliharaan', 'paras' => ['Silakan keluar lalu masuk kembali ke aplikasi. Laporkan kendala ke tim IT melalui helpdesk.']],
                ],
                'set' => 'both',
                'cat' => 'IT INFO',
            ],
            [
                'slug' => 'cuti-bersama-idul-fitri',
                'img' => 'libur-nasional',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Cuti Bersama Idul Fitri',
                'status' => 'inactive',
                'date' => '02 Maret 2026',
                'stamp' => '02-03-2026 09:00:00',
                'author' => 'Human Capital',
                'caption' => 'Informasi cuti bersama',
                'excerpt' => 'Informasi cuti bersama Idul Fitri dan ketentuan pemotongan jatah cuti tahunan.',
                'lead' => 'Informasi <em>cuti bersama</em> Idul Fitri dan ketentuan pemotongan jatah cuti tahunan.',
                'sections' => [
                    ['id' => 'ketentuan', 'h' => 'Ketentuan cuti bersama', 'paras' => ['Cuti bersama mengikuti keputusan pemerintah dan memotong jatah cuti tahunan sesuai kebijakan perusahaan.']],
                    ['id' => 'piket', 'h' => 'Jadwal piket', 'paras' => ['Tim dengan layanan kritis akan menerima jadwal piket dari atasan masing-masing.']],
                ],
                'set' => 'both',
                'cat' => 'HR POLICY',
            ],
            [
                'slug' => 'gathering-akhir-tahun-2025',
                'img' => 'happy-new-year',
                'tag' => 'INFORMASI',
                'tagLabel' => 'Informasi',
                'title' => 'Gathering Akhir Tahun 2025',
                'status' => 'inactive',
                'date' => '01 Desember 2025',
                'stamp' => '01-12-2025 11:00:00',
                'author' => 'Corporate Communication',
                'caption' => 'Dokumentasi gathering akhir tahun',
                'excerpt' => 'Gathering akhir tahun menjadi momen untuk merayakan kerja keras sepanjang tahun bersama seluruh keluarga besar perusahaan.',
                'lead' => 'Gathering akhir tahun menjadi momen untuk <em>merayakan kerja keras</em> sepanjang tahun bersama seluruh keluarga besar perusahaan.',
                'sections' => [
                    ['id' => 'agenda', 'h' => 'Agenda acara', 'paras' => ['Acara diisi dengan permainan tim, makan malam bersama, dan pengumuman penghargaan karyawan.']],
                    ['id' => 'pendaftaran', 'h' => 'Pendaftaran', 'paras' => ['Konfirmasi kehadiran melalui menu Forms → Confirmation paling lambat satu minggu sebelum acara.']],
                ],
                'set' => 'both',
                'cat' => 'BUDAYA KERJA',
            ],
            [
                'slug' => 'integrity',
                'img' => null,
                'tag' => 'BUDAYA KERJA',
                'cat' => 'BUDAYA KERJA',
                'tagLabel' => 'Budaya Kerja',
                'title' => 'INTEGRITY',
                'status' => 'active',
                'set' => 'v2',
                'date' => '06 Maret 2024',
                'stamp' => '06-03-2024 09:59:54',
                'author' => 'Corporate Culture',
                'caption' => '',
                'excerpt' => 'Pilar nilai perusahaan mengenai integritas, kepatuhan etika bisnis, dan tata kelola yang baik dalam setiap keputusan kerja.',
                'lead' => 'Pilar nilai perusahaan mengenai <em>integritas</em>, kepatuhan etika bisnis, dan tata kelola yang baik dalam setiap keputusan kerja.',
                'sections' => [
                    ['id' => 'makna', 'h' => 'Makna integritas', 'paras' => ['Integritas berarti bertindak jujur dan konsisten, baik ketika diawasi maupun tidak. Nilai ini menjadi dasar kepercayaan pelanggan, mitra, dan sesama rekan kerja.']],
                    ['id' => 'praktik', 'h' => 'Dalam praktik sehari-hari', 'paras' => ['Gunakan aset perusahaan sesuai peruntukannya, laporkan konflik kepentingan sejak awal, dan tolak segala bentuk gratifikasi.', 'Catat waktu kerja, lembur, dan klaim biaya sesuai kondisi sebenarnya.']],
                    ['id' => 'pelaporan', 'h' => 'Saluran pelaporan', 'paras' => ['Jika menemukan pelanggaran, laporkan melalui atasan langsung atau tim Human Capital. Identitas pelapor dijaga kerahasiaannya.']],
                ],
            ],
            [
                'slug' => 'it-bm-penarikan-surat-elektronik',
                'img' => null,
                'tag' => 'IT INFO',
                'cat' => 'IT INFO',
                'tagLabel' => 'IT Info',
                'title' => '[IT BM] [PENTING] Pengingat Penarikan Surat Elektronik',
                'status' => 'active',
                'set' => 'v2',
                'date' => '11 Oktober 2021',
                'stamp' => '11-10-2021 11:52:04',
                'author' => 'IT Business Management',
                'caption' => '',
                'excerpt' => 'Langkah-langkah teknis sinkronisasi dan aturan retensi mail client Microsoft Outlook agar email kantor tetap aman dan tidak hilang.',
                'lead' => 'Langkah-langkah teknis <em>sinkronisasi</em> dan aturan retensi mail client Microsoft Outlook agar email kantor tetap aman dan tidak hilang.',
                'sections' => [
                    ['id' => 'sinkronisasi', 'h' => 'Sinkronisasi mail client', 'paras' => ['Pastikan Microsoft Outlook terhubung ke server email kantor dan mode sinkronisasi diatur untuk menyimpan salinan di server.', 'Hindari mengatur Outlook untuk menghapus email dari server setelah diunduh.']],
                    ['id' => 'retensi', 'h' => 'Aturan retensi', 'paras' => ['Email lama akan diarsipkan otomatis sesuai kebijakan retensi. Simpan dokumen penting di penyimpanan bersama, bukan hanya di kotak masuk.']],
                    ['id' => 'bantuan', 'h' => 'Butuh bantuan?', 'paras' => ['Hubungi tim IT melalui helpdesk jika email tidak tersinkron atau kotak surat penuh.']],
                ],
            ],
            [
                'slug' => 'it-infra-rule-permintaan-email',
                'img' => null,
                'tag' => 'INFRASTRUKTUR',
                'cat' => 'INFRASTRUKTUR',
                'tagLabel' => 'Infrastruktur',
                'title' => '[IT INFRA] Sosialisasi rule permintaan email baru',
                'status' => 'active',
                'set' => 'v2',
                'date' => '05 Januari 2021',
                'stamp' => '05-01-2021 18:58:27',
                'author' => 'IT Infrastructure',
                'caption' => '',
                'excerpt' => 'Prosedur pengajuan akun korporat domain resmi serta syarat persetujuan dari masing-masing atasan.',
                'lead' => 'Prosedur pengajuan akun korporat domain resmi serta <em>syarat persetujuan</em> dari masing-masing atasan.',
                'sections' => [
                    ['id' => 'syarat', 'h' => 'Syarat pengajuan', 'paras' => ['Permintaan email baru diajukan oleh atasan langsung untuk karyawan yang sudah memiliki NIP aktif.', 'Sertakan nama lengkap, NIP, unit kerja, dan tanggal mulai bekerja.']],
                    ['id' => 'alur', 'h' => 'Alur persetujuan', 'paras' => ['Permintaan diverifikasi oleh Human Capital, lalu diproses tim IT Infrastructure. Akun dikirim ke atasan yang mengajukan.']],
                    ['id' => 'ketentuan', 'h' => 'Ketentuan penggunaan', 'paras' => ['Email korporat hanya digunakan untuk keperluan pekerjaan dan akan dinonaktifkan ketika karyawan tidak lagi bekerja di perusahaan.']],
                ],
            ],
            [
                'slug' => 'pengajuan-lembur',
                'img' => null,
                'tag' => 'HR POLICY',
                'cat' => 'HR POLICY',
                'tagLabel' => 'HR Policy',
                'title' => 'PENGAJUAN LEMBUR',
                'status' => 'active',
                'set' => 'v2',
                'date' => '15 September 2020',
                'stamp' => '15-09-2020 13:04:45',
                'author' => 'Human Capital',
                'caption' => '',
                'excerpt' => 'Tata cara klaim jam kerja lembur melalui sistem ESS sebelum batas tanggal cut-off.',
                'lead' => 'Tata cara klaim jam kerja lembur melalui sistem ESS <em>sebelum batas tanggal cut-off</em>.',
                'sections' => [
                    ['id' => 'pengajuan', 'h' => 'Cara mengajukan', 'paras' => ['Ajukan lembur melalui menu Overtime → Propose, isi tanggal, jam mulai, jam selesai, dan alasan lembur, lalu pilih approver.']],
                    ['id' => 'cut-off', 'h' => 'Batas cut-off', 'paras' => ['Pengajuan yang belum disetujui setelah tanggal cut-off akan diproses pada periode penggajian berikutnya.']],
                    ['id' => 'catatan', 'h' => 'Catatan', 'paras' => ['Lembur di hari libur wajib mendapat persetujuan atasan sebelum dilaksanakan.']],
                ],
            ],
            [
                'slug' => 'mobile-apps-matta',
                'img' => null,
                'tag' => 'APLIKASI',
                'cat' => 'APLIKASI',
                'tagLabel' => 'Aplikasi',
                'title' => 'Selamat Datang di Mobile Apps MATTA',
                'status' => 'active',
                'set' => 'v2',
                'date' => '02 September 2020',
                'stamp' => '02-09-2020 17:31:09',
                'author' => 'IT Workplace',
                'caption' => '',
                'excerpt' => 'Panduan instalasi dan penggunaan aplikasi seluler resmi MATTA untuk mempermudah presensi dan pengajuan dari mana saja.',
                'lead' => 'Panduan instalasi dan penggunaan aplikasi seluler resmi MATTA untuk <em>mempermudah</em> presensi dan pengajuan dari mana saja.',
                'sections' => [
                    ['id' => 'instalasi', 'h' => 'Instalasi', 'paras' => ['Unduh aplikasi MATTA dari tautan resmi yang dibagikan tim IT. Jangan memasang aplikasi dari sumber lain.']],
                    ['id' => 'masuk', 'h' => 'Masuk ke aplikasi', 'paras' => ['Gunakan akun email kantor yang sama dengan versi web. Aktifkan izin lokasi agar presensi dapat tercatat.']],
                    ['id' => 'fitur', 'h' => 'Fitur utama', 'paras' => ['Clock In / Clock Out, pengajuan cuti dan izin, slip gaji, serta pengumuman perusahaan dalam satu aplikasi.']],
                ],
            ],
        ];
    }

    /**
     * Feeds list page.
     */
    public function feeds()
    {
        $u = $this->getUserContext();
        $feeds = $this->getFeedsCatalog();
        $activeCount = count(array_filter($feeds, fn($f) => $f['status'] === 'active'));
        $inactiveCount = count(array_filter($feeds, fn($f) => $f['status'] !== 'active'));

        return view('matta.feeds', compact('u', 'feeds', 'activeCount', 'inactiveCount'));
    }

    /**
     * Feed detail page.
     */
    public function feedDetail(Request $request)
    {
        $u = $this->getUserContext();
        $feeds = $this->getFeedsCatalog();
        return view('matta.feed-detail', compact('u', 'feeds'));
    }

    /**
     * Health declaration page.
     */
    public function health()
    {
        $u = $this->getUserContext();
        return view('matta.health', compact('u'));
    }

    /**
     * Save Health form submission into trx_tbl_absensi_kesehatan.
     */
    public function submitHealth(Request $request)
    {
        $u = $this->getUserContext();
        try {
            $trx = new AbsensiKesehatan();
            $trx->karyawan = $u['nip'];
            $trx->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $u['nip'], 'jabatan' => $u['role']]);
            $trx->tanggal_input = date('Y-m-d');
            $trx->ring_ke = 'ring1';
            $trx->ring_isian = $request->input('r1_condition') === 'tidak-sehat' ? 'Ada' : 'Tidak';
            $trx->siapa = $u['name'];
            $trx->sakit = $request->input('r1_note') ?: 'Sehat';
            $trx->tanggal_sakit = date('Y-m-d');
            $trx->save();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('submitModule error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Payroll Slip page.
     */
    public function payroll()
    {
        $u = $this->getUserContext();
        $nik = $u['nip'];
        $monthsId = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $slips = [];
        $hco = explode(',', optional(\App\Models\MtrUtility::find(32))->informasi ?? '');
        $isHco = in_array(auth()->user()->nik ?? '', $hco);

        try {
            $rows = TrxPayroll::where('nik', $nik)->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->limit(24)->get();
            foreach ($rows as $r) {
                $gapok = (int) ($r->gapok ?: 8500000);
                $ot = (int) ($r->overtime ?: 1250000);
                $tunjangan = 1850000 + $ot;
                $potongan = (int) ($r->pajak ?: 425000) + 255000;
                $thp = $gapok + $tunjangan - $potongan;
                $mName = $monthsId[(int) $r->bulan] ?? 'Oktober';
                $yr = (string) ($r->tahun ?: '2026');
                $paidAt = $r->paid_at ? Carbon::parse($r->paid_at)->format('d M Y') : ('25 ' . substr($mName, 0, 3) . ' ' . $yr);

                $canDownload = $isHco || (!empty($r->paid_at) && (Carbon::parse($r->paid_at)->isPast() || Carbon::parse($r->paid_at)->isToday()));
                $encId = (new \App\Http\Controllers\OpenDataController)->encryptWithKey($r->id);
                $pdfUrl = route('payroll.slip.detail', [$encId, 'view' => 'pdf']);

                $slips[] = [
                    'id' => $r->id,
                    'month_num' => (int) $r->bulan,
                    'month' => $mName,
                    'year' => $yr,
                    'name' => $u['name'],
                    'nip' => $nik,
                    'paid_at' => $paidAt,
                    'salary_fmt' => 'Rp ' . number_format($gapok, 0, ',', '.'),
                    'allowance_fmt' => 'Rp ' . number_format($tunjangan, 0, ',', '.'),
                    'deduction_fmt' => 'Rp ' . number_format($potongan, 0, ',', '.'),
                    'thp_fmt' => 'Rp ' . number_format($thp, 0, ',', '.'),
                    'status' => 'Paid',
                    'can_download' => $canDownload,
                    'pdf_url' => $canDownload ? $pdfUrl : null,
                ];
            }

            // Also check TrxPayrollLog
            $logRows = \App\Models\TrxPayrollLog::where('nik', $nik)->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->limit(24)->get();
            foreach ($logRows as $r) {
                $gapok = (int) ($r->gapok ?: 8500000);
                $ot = (int) ($r->overtime ?: 1250000);
                $tunjangan = 1850000 + $ot;
                $potongan = (int) ($r->pajak ?: 425000) + 255000;
                $thp = $gapok + $tunjangan - $potongan;
                $mName = $monthsId[(int) $r->bulan] ?? 'Oktober';
                $yr = (string) ($r->tahun ?: '2026');
                $paidAt = $r->paid_at ? Carbon::parse($r->paid_at)->format('d M Y') : ('25 ' . substr($mName, 0, 3) . ' ' . $yr);

                $canDownload = $isHco || (!empty($r->paid_at) && (Carbon::parse($r->paid_at)->isPast() || Carbon::parse($r->paid_at)->isToday()));
                $encId = (new \App\Http\Controllers\OpenDataController)->encryptWithKey($r->id);
                $pdfUrl = route('payroll.slip.detail', [$encId, 'view' => 'pdf_h']);

                $slips[] = [
                    'id' => $r->id,
                    'month_num' => (int) $r->bulan,
                    'month' => $mName,
                    'year' => $yr,
                    'name' => $u['name'],
                    'nip' => $nik,
                    'paid_at' => $paidAt,
                    'salary_fmt' => 'Rp ' . number_format($gapok, 0, ',', '.'),
                    'allowance_fmt' => 'Rp ' . number_format($tunjangan, 0, ',', '.'),
                    'deduction_fmt' => 'Rp ' . number_format($potongan, 0, ',', '.'),
                    'thp_fmt' => 'Rp ' . number_format($thp, 0, ',', '.'),
                    'status' => 'Paid',
                    'can_download' => $canDownload,
                    'pdf_url' => $canDownload ? $pdfUrl : null,
                ];
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Payroll fetch error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        if (empty($slips) && request()->has('demo')) {
            $base = (int) (optional($u['karyawan'])->gaji_pokok ?: 8500000);
            if ($base < 1000000) $base = 8500000;
            $allow = 2350000;
            $ded = 650000;
            $thp = $base + $allow - $ded;
            $slips = [
                [
                    'id' => 1,
                    'month_num' => 10, 'month' => 'Oktober', 'year' => '2026',
                    'name' => $u['name'], 'nip' => $nik,
                    'paid_at' => '25 Okt 2026',
                    'salary_fmt' => 'Rp ' . number_format($base, 0, ',', '.'),
                    'allowance_fmt' => 'Rp ' . number_format($allow, 0, ',', '.'),
                    'deduction_fmt' => 'Rp ' . number_format($ded, 0, ',', '.'),
                    'thp_fmt' => 'Rp ' . number_format($thp, 0, ',', '.'),
                    'status' => 'Paid',
                    'can_download' => true,
                    'pdf_url' => null,
                ],
            ];
        }

        $availableYears = collect($slips)->pluck('year')->unique()->sortDesc()->values()->all();
        if (empty($availableYears)) {
            $availableYears = [(string) date('Y'), (string) (date('Y') - 1)];
        }
        $monthsList = array_values($monthsId);

        return view('matta.payroll-slip', compact('u', 'slips', 'availableYears', 'monthsList'));
    }

    /**
     * Build attendance log & daily duration dataset from trx_tbl_attendance for the logged-in user.
     */
    private function buildAttendanceData(array $u, array $filters = []): array
    {
        $nik = $u['nip'];
        $query = Attendance::with(['_att_nik._perusahaan', '_att_nik._divisi', '_att_nik._departemen', '_att_nik._wg', '_fp_trx']);

        $selectedNik = $filters['str_att_nik_nama'] ?? '';
        $allowedNiks = $filters['allowed_niks'] ?? [$nik];

        if (!empty($selectedNik)) {
            $query->where('att_nik', $selectedNik);
        } else {
            $query->whereIn('att_nik', $allowedNiks);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('att_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('att_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['att_type'])) {
            $query->whereIn('att_type', (array) $filters['att_type']);
        }
        if (!empty($filters['att_status'])) {
            $query->whereIn('att_status', (array) $filters['att_status']);
        }
        if (!empty($filters['att_data_src'])) {
            $query->whereIn('att_data_src', (array) $filters['att_data_src']);
        }
        if (!empty($filters['wg_id'])) {
            $wgNiks = Karyawan::whereIn('wg_id', (array) $filters['wg_id'])->pluck('nik')->all();
            $query->whereIn('att_nik', $wgNiks);
        }
        if (!empty($filters['perusahaan'])) {
            $corpNiks = Karyawan::whereIn('kode_perusahaan', (array) $filters['perusahaan'])->pluck('nik')->all();
            $query->whereIn('att_nik', $corpNiks);
        }

        $dbRows = $query->orderBy('att_timestamp', 'desc')
            ->orderBy('id', 'desc')
            ->limit(300)
            ->get();

        $rawLogs = [];

        foreach ($dbRows as $r) {
            $ts = Carbon::parse($r->att_timestamp ?: $r->att_date);
            $dateStr = $ts->format('d-m-Y');
            $timestampStr = $ts->format('d-m-Y H:i:s');
            $rawType = strtolower($r->att_type ?? 'clock-in');
            $isClockIn = in_array($rawType, ['clock-in', 'check-in']);
            $action = $r->att_type ?: ($isClockIn ? 'Clock-In' : 'Clock-out');
            $status = $r->att_status ?: ($isClockIn ? 'Book-In' : 'Book-out');
            $lat = (string) ($r->att_lat ?? '-6.2088');
            $lng = (string) ($r->att_long ?? '106.8227');
            $addr = $r->att_address ?: 'Kantor Pusat (HQ), Jakarta';
            $src = ($r->id_fp_trx && $r->id_fp_trx > 0) ? 'FingerPrint' : 'MATTA';
            $typeLabel = $r->att_data_src ?: 'Regular';

            $karyawan = $r->_att_nik;
            $name = $karyawan?->nama ?? ($u['nip'] === $r->att_nik ? $u['name'] : $r->att_nik);
            $company = optional($karyawan?->_perusahaan)->nama ?? $u['company'];
            $directorate = optional($karyawan?->_divisi)->nama ?? $u['directorate'];
            $division = optional($karyawan?->_divisi)->nama ?? $u['division'];
            $department = optional($karyawan?->_departemen)->nama ?? $u['department'];
            $wg = optional($karyawan?->_wg)->wg_name ?? $u['wg'];

            $rawLogs[] = [
                'id' => $r->id,
                'dateStr' => $dateStr,
                'isoDate' => $ts->format('Y-m-d'),
                'timestamp' => $timestampStr,
                'name' => $name,
                'nip' => $r->att_nik,
                'id_fp' => $r->id_fp_trx ?: ($karyawan?->id_fp ?? '-'),
                'company' => $company,
                'directorate' => $directorate,
                'division' => $division,
                'department' => $department,
                'location' => 'HQ',
                'wg' => $wg,
                'action' => $action,
                'isClockIn' => $isClockIn,
                'lng' => $lng,
                'lat' => $lat,
                'address' => $addr,
                'status' => $status,
                'delay' => $r->att_clock_gap ?: '-',
                'type' => $typeLabel,
                'source' => $src,
            ];
        }

        return [$rawLogs, []];
    }

    /**
     * Attendance Log page.
     */
    public function attendanceLog()
    {
        $u = $this->getUserContext();
        $userNik = $u['nip'];

        $date_from = request('date_from', '');
        $date_to = request('date_to', '');
        $str_att_nik_nama = request('str_att_nik_nama', '');
        $arr_att_type = (array) request('att_type', []);
        $arr_att_status = (array) request('att_status', []);
        $arr_att_data_src = (array) request('att_data_src', []);
        $arr_wg_id = (array) request('wg_id', []);
        $arr_perusahaan = (array) request('perusahaan', []);

        $isFiltered = request()->filled('date_from') || request()->filled('date_to') || request()->filled('str_att_nik_nama') || !empty($arr_att_type) || !empty($arr_att_status) || !empty($arr_att_data_src) || !empty($arr_wg_id) || !empty($arr_perusahaan);

        // Filter options for select & checkboxes
        $all_att_type = ['Check-in', 'Clock-in', 'Clock-out', 'Leave'];
        $all_att_status = ['Book-in', 'Book-out', 'Early-out', 'Late-in', 'Leave'];
        $all_att_data_src = ['Leave', 'Overtime', 'Regular'];
        $working_groups = \App\Models\WorkingGroup::orderBy('id', 'asc')->get();
        $companies = \App\Models\Perusahaan::whereNotIn('id', [0, 3])->orderBy('nama', 'asc')->get();

        // Karyawan options: current user + direct & indirect subordinates
        $sub1 = Karyawan::where('layer1', $userNik)->pluck('nik')->all();
        $arr_nik = array_merge([$userNik], $sub1);
        foreach ($sub1 as $s1) {
            $sub2 = Karyawan::where('layer1', $s1)->pluck('nik')->all();
            $arr_nik = array_merge($arr_nik, $sub2);
        }
        $arr_nik = array_unique($arr_nik);
        $karyawanOptions = Karyawan::whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get(['nik as att_nik', 'nama']);

        [$rawLogs] = $this->buildAttendanceData($u, [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'str_att_nik_nama' => $str_att_nik_nama,
            'att_type' => $arr_att_type,
            'att_status' => $arr_att_status,
            'att_data_src' => $arr_att_data_src,
            'wg_id' => $arr_wg_id,
            'perusahaan' => $arr_perusahaan,
            'allowed_niks' => $arr_nik
        ]);

        return view('matta.attendance-log', compact(
            'u', 'rawLogs', 'date_from', 'date_to', 'str_att_nik_nama',
            'arr_att_type', 'arr_att_status', 'arr_att_data_src', 'arr_wg_id', 'arr_perusahaan',
            'all_att_type', 'all_att_status', 'all_att_data_src', 'working_groups', 'companies', 'karyawanOptions',
            'isFiltered'
        ));
    }

    /**
     * Attendance Recap page.
     */
    public function attendanceRecap()
    {
        $u = $this->getUserContext();
        $userNik = $u['nip'];

        $date_from = request('date_from', '');
        $date_to = request('date_to', '');
        $isFiltered = request()->filled('date_from') && request()->filled('date_to');

        $str_att_nik_nama = request('str_att_nik_nama', '');
        $wg_id = (array) request('wg_id', []);
        $perusahaan = (array) request('perusahaan', []);

        // Determine list of karyawan to recap: current user + direct & 2nd level subordinates
        $arr_nik = [$userNik];
        try {
            $sub1 = Karyawan::where('layer1', $userNik)->pluck('nik')->all();
            foreach ($sub1 as $s1) {
                $arr_nik[] = $s1;
                $sub2 = Karyawan::where('layer1', $s1)->pluck('nik')->all();
                foreach ($sub2 as $s2) {
                    $arr_nik[] = $s2;
                }
            }
        } catch (\Throwable $e) {}
        $arr_nik = array_values(array_unique($arr_nik));

        $karyawanOptions = Karyawan::whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get();
        $working_groups = \App\Models\WorkingGroup::orderBy('id', 'asc')->get();
        $companies = \App\Models\Perusahaan::whereNotIn('id', [0, 3])->orderBy('nama', 'asc')->get();

        $recaps = [];

        // Di awal buka tabel kosong. Data baru muncul dan dihitung jika user melakukan filter
        if ($isFiltered) {
            try {
                $filterNik = $arr_nik;
                if (!empty($str_att_nik_nama)) {
                    $filterNik = array_intersect($filterNik, [$str_att_nik_nama]);
                }

                $karyawanQuery = Karyawan::with(['_wg', '_level', '_subdepartemen', '_departemen'])
                    ->whereIn('nik', $filterNik);

                if (!empty($wg_id)) {
                    $karyawanQuery->whereIn('working_group', $wg_id);
                }
                if (!empty($perusahaan)) {
                    $karyawanQuery->whereIn('perusahaan', $perusahaan);
                }

                $karyawanList = $karyawanQuery->get();

                // Cache holidays globally
                $holidays = \App\Models\Holiday::pluck('tanggal')->all();

                // Build list of calendar dates between date_from and date_to
                $arr_date = [];
                $cur = strtotime($date_from);
                $end = strtotime($date_to);
                if ($cur && $end && $cur <= $end) {
                    while ($cur <= $end) {
                        $arr_date[] = date('Y-m-d', $cur);
                        $cur = strtotime('+1 day', $cur);
                    }
                }

                // Exception date as in original codebase
                $arr_date = array_values(array_diff($arr_date, ['2024-09-23']));

                foreach ($karyawanList as $k) {
                    $nik = $k->nik;
                    $arr_base = [];
                    $arr_clock = [];
                    $tglBergabung = $k->tanggal_bergabung;

                    // Preload user's cuti & sakit within filter period
                    $cutis = TrxCuti::where('nik_karyawan', $nik)
                        ->whereIn('status_cuti', [4, 6])
                        ->where(function($q) use ($date_from, $date_to) {
                            $q->whereDate('tanggal_mulai', '<=', $date_to)
                              ->whereDate('tanggal_akhir', '>=', $date_from);
                        })
                        ->get(['tanggal_mulai', 'tanggal_akhir']);

                    $sakits = TrxSakit::where('nik_karyawan', $nik)
                        ->whereIn('status_sakit', [4, 6])
                        ->where(function($q) use ($date_from, $date_to) {
                            $q->whereDate('tanggal_mulai', '<=', $date_to)
                              ->whereDate('tanggal_akhir', '>=', $date_from);
                        })
                        ->get(['tanggal_mulai', 'tanggal_akhir']);

                    // Calculate base working days
                    if (optional($k->_wg)->is_reguler == 1) {
                        foreach ($arr_date as $d) {
                            $w = (int) date('w', strtotime($d));
                            if ($w !== 0 && $w !== 6) { // not weekend
                                if (!in_array($d, $holidays)) { // not holiday
                                    $isCuti = false;
                                    foreach ($cutis as $c) {
                                        if ($c->tanggal_mulai <= $d && $c->tanggal_akhir >= $d) {
                                            $isCuti = true;
                                            break;
                                        }
                                    }
                                    if (!$isCuti) {
                                        $isSakit = false;
                                        foreach ($sakits as $s) {
                                            if ($s->tanggal_mulai <= $d && $s->tanggal_akhir >= $d) {
                                                $isSakit = true;
                                                break;
                                            }
                                        }
                                        if (!$isSakit && (!$tglBergabung || $tglBergabung <= $d)) {
                                            $arr_base[] = $d;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $workDays = WorkingDay::where('nik', $nik)
                            ->whereIn('working_date', $arr_date)
                            ->pluck('working_date')
                            ->all();

                        if (empty($workDays)) {
                            // Fallback to weekdays if no custom schedule
                            foreach ($arr_date as $d) {
                                $w = (int) date('w', strtotime($d));
                                if ($w !== 0 && $w !== 6 && !in_array($d, $holidays)) {
                                    $arr_base[] = $d;
                                }
                            }
                        } else {
                            foreach ($workDays as $wd) {
                                $isCuti = false;
                                foreach ($cutis as $c) {
                                    if ($c->tanggal_mulai <= $wd && $c->tanggal_akhir >= $wd) {
                                        $isCuti = true;
                                        break;
                                    }
                                }
                                if (!$isCuti) {
                                    $isSakit = false;
                                    foreach ($sakits as $s) {
                                        if ($s->tanggal_mulai <= $wd && $s->tanggal_akhir >= $wd) {
                                            $isSakit = true;
                                            break;
                                        }
                                    }
                                    if (!$isSakit && (!$tglBergabung || $tglBergabung <= $wd)) {
                                        $arr_base[] = $wd;
                                    }
                                }
                            }
                        }
                    }

                    // Count clock-ins within base working days
                    if (!empty($arr_base)) {
                        $clockIns = Attendance::where('att_nik', $nik)
                            ->whereIn('att_date', $arr_base)
                            ->where('att_type', 'Clock-in')
                            ->pluck('att_date')
                            ->unique()
                            ->all();
                        $arr_clock = $clockIns;
                    }

                    $jmlHariKerja = count($arr_base);
                    $jmlKehadiran = count($arr_clock);
                    $persentase = $jmlHariKerja > 0 ? round(($jmlKehadiran / $jmlHariKerja) * 100) : 0;

                    // Layer 1 info
                    $layer1 = null;
                    if (!empty($k->layer1)) {
                        $layer1 = Karyawan::where('nik', $k->layer1)->first();
                    }

                    $recaps[] = [
                        'tanggal_awal' => Carbon::parse($date_from)->format('d-m-Y'),
                        'tanggal_akhir' => Carbon::parse($date_to)->format('d-m-Y'),
                        'working_group' => optional($k->_wg)->wg_name ?? ($u['wg'] ?? '-'),
                        'nip' => $k->nik,
                        'nama_karyawan' => $k->nama,
                        'level' => optional($k->_level)->level ?? '-',
                        'departemen' => optional($k->_subdepartemen)->nama ?? (optional($k->_departemen)->nama ?? '-'),
                        'email_karyawan' => $k->email ?? '-',
                        'nama_layer_1' => $layer1 ? $layer1->nama : '-',
                        'email_layer_1' => $layer1 ? $layer1->email : '-',
                        'jml_hari_kerja' => $jmlHariKerja,
                        'jml_kehadiran' => $jmlKehadiran,
                        'persentase_kehadiran' => $persentase . ' %',
                        'persentase_num' => $persentase,
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('AttendanceRecap error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            }
        }

        if (empty($recaps) && request()->has('demo')) {
            $recaps[] = [
                'tanggal_awal' => !empty($date_from) ? Carbon::parse($date_from)->format('d-m-Y') : '01-11-2026',
                'tanggal_akhir' => !empty($date_to) ? Carbon::parse($date_to)->format('d-m-Y') : '30-11-2026',
                'working_group' => $u['wg'] ?? 'Reguler - 0830-1730',
                'nip' => $u['nip'],
                'nama_karyawan' => $u['name'],
                'level' => $u['role'] ?? 'Frontend Developer',
                'departemen' => $u['department'] ?? 'IT Workplace',
                'email_karyawan' => $u['email'] ?? 'daffa.dzaky@alita.id',
                'nama_layer_1' => $u['approver'] ?? 'Muhammad Rizky',
                'email_layer_1' => $u['approver_nip'] ? 'rizky@alita.id' : '-',
                'jml_hari_kerja' => 21,
                'jml_kehadiran' => 20,
                'persentase_kehadiran' => '95 %',
                'persentase_num' => 95,
            ];
        }

        return view('matta.attendance-recap', compact('u', 'recaps', 'date_from', 'date_to', 'isFiltered', 'karyawanOptions', 'working_groups', 'companies', 'str_att_nik_nama', 'wg_id', 'perusahaan'));
    }

    /**
     * Overtime Propose page.
     */
    public function propose()
    {
        $u = $this->getUserContext();
        $nik = $u['nip'];

        $approvers = [];
        try {
            $supervisors = Karyawan::where('is_aktif', 1)
                ->whereNotIn('nik', ['Hco.all', 'IT.BM', 'IT Infras', $nik])
                ->orderBy('nama', 'asc')
                ->limit(30)
                ->get();
            foreach ($supervisors as $s) {
                $approvers[] = ['nik' => $s->nik, 'nama' => $s->nama];
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('propose approvers error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
        if (empty($approvers)) {
            $approvers = [
                ['nik' => '01031502441', 'nama' => 'Muhammad Rizky'],
                ['nik' => '01031603882', 'nama' => 'Dewi Kaniawati'],
                ['nik' => '01031401290', 'nama' => 'Bambang Supriyanto'],
            ];
        }

        $otTypes = [];
        try {
            $otTypes = \App\Models\OvertimeType::all();
        } catch (\Throwable $e) {
            // ignore
        }

        $date_from = request('date_from', '');
        $date_to = request('date_to', '');
        $filter_status = request('status', '');

        $proposals = [];
        try {
            $query = Overtime::with(['_ot_maker_by', '_ot_approver_by', '_ot_type_code']);
            if (auth()->user()->userrole === 'nhc') {
                $query->where('ot_maker_by', $nik);
            } else {
                $query->where(function($q) use ($nik) {
                    $q->where('ot_maker_by', $nik)->orWhere('ot_approver_by', $nik);
                });
            }

            if (!empty($date_from)) {
                $query->whereDate('ot_start_at', '>=', $date_from);
            }
            if (!empty($date_to)) {
                $query->whereDate('ot_start_at', '<=', $date_to);
            }
            if (!empty($filter_status)) {
                $query->where('ot_approver_status', $filter_status);
            }

            $ots = $query->orderBy('id', 'desc')->limit(100)->get();

            foreach ($ots as $o) {
                $created = $o->ot_maker_at ? Carbon::parse($o->ot_maker_at) : Carbon::parse($o->getRawOriginal('created_at') ?: now());
                $startAt = $o->ot_start_at ? Carbon::parse($o->ot_start_at) : null;
                $endAt = $o->ot_end_at ? Carbon::parse($o->ot_end_at) : null;

                $makerName = optional($o->_ot_maker_by)->nama ?? ($o->ot_maker_by === $nik ? $u['name'] : $o->ot_maker_by);
                $makerNip = $o->ot_maker_by ?: $nik;

                $appName = optional($o->_ot_approver_by)->nama ?? ($o->ot_approver_by ?: '-');
                $appNip = $o->ot_approver_by ?: '-';

                $st = (string) $o->ot_approver_status;
                if (in_array(strtolower($st), ['approve', 'approved', '1'])) {
                    $stLabel = 'Approve';
                    $stColor = 'green';
                    $stKey = 'approve';
                } elseif (in_array(strtolower($st), ['reject', 'rejected', '2'])) {
                    $stLabel = 'Reject';
                    $stColor = 'red';
                    $stKey = 'reject';
                } else {
                    $stLabel = 'Waiting';
                    $stColor = 'orange';
                    $stKey = 'waiting';
                }

                $typeName = optional($o->_ot_type_code)->ot_type ?? ($o->ot_type_code ?: 'Regular');

                $proposals[] = [
                    'id' => $o->id,
                    'created_at' => $created->format('d-m-Y H:i:s'),
                    'created_by' => $makerName,
                    'created_nip' => $makerNip,
                    'approved_by' => $appName,
                    'approved_nip' => $appNip,
                    'status_label' => $stLabel,
                    'status_color' => $stColor,
                    'status_key' => $stKey,
                    'start' => $startAt ? $startAt->format('d-m-Y H:i:s') : '-',
                    'finish' => $endAt ? $endAt->format('d-m-Y H:i:s') : '-',
                    'title' => $o->ot_title ?: ($o->ot_desc ?: 'Penyelesaian pekerjaan lembur'),
                    'type' => $typeName,
                    'desc' => $o->ot_desc,
                    'edit_url' => route('overtime.edit', $o->id),
                ];
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('propose fetch error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        if (empty($proposals) && request()->has('demo')) {
            $proposals = [
                [
                    'id' => 101,
                    'created_at' => '23-10-2026 17:40:00',
                    'created_by' => $u['name'],
                    'created_nip' => $nik,
                    'approved_by' => $u['approver'],
                    'approved_nip' => $u['approver_nip'],
                    'status_label' => 'Approve',
                    'status_color' => 'green',
                    'status_key' => 'approve',
                    'start' => '23-10-2026 18:30:00',
                    'finish' => '23-10-2026 21:30:00',
                    'title' => 'Deployment aplikasi dan pengujian integrasi produksi',
                    'type' => 'Hari Kerja',
                    'desc' => 'Deployment aplikasi dan pengujian integrasi produksi',
                    'edit_url' => '#',
                ],
            ];
        }

        return view('matta.propose', compact('u', 'approvers', 'otTypes', 'proposals', 'date_from', 'date_to', 'filter_status'));
    }

    /**
     * Save Overtime Propose submission into trx_tbl_overtime.
     */
    public function submitPropose(Request $request)
    {
        $u = $this->getUserContext();
        try {
            $makerBy = $request->input('ot_maker_by') ?: $u['nip'];
            $title = $request->input('ot_title') ?: $request->input('title', 'Overtime ' . date('Y-m-d'));
            $desc = $request->input('ot_desc') ?: $request->input('reason', $title);
            $type = $request->input('ot_type_code') ?: $request->input('type', 'Tipe 1');

            if ($request->filled('ot_start_at')) {
                $startDt = Carbon::parse($request->input('ot_start_at'));
            } else {
                $date = $request->input('date', date('Y-m-d'));
                $start = $request->input('start', '18:00');
                $startDt = Carbon::parse($date . ' ' . $start);
            }

            if ($request->filled('ot_end_at')) {
                $endDt = Carbon::parse($request->input('ot_end_at'));
            } else {
                $date = $request->input('date', date('Y-m-d'));
                $end = $request->input('end', '20:00');
                $endDt = Carbon::parse($date . ' ' . $end);
            }

            $diffSec = max(0, $startDt->diffInSeconds($endDt));

            // Approver layer1
            $approverBy = $request->input('ot_approver_by') ?: ($request->input('approver') ?: $u['approver_nip']);
            if (empty($approverBy)) {
                $karyawan = Karyawan::where('nik', $makerBy)->first();
                $approverBy = $karyawan ? $karyawan->layer1 : $u['approver_nip'];
            }

            // Find overtime type
            $typeModel = null;
            try {
                $typeModel = \App\Models\OvertimeType::where('ot_type', $type)->orWhere('id', $type)->first();
            } catch (\Throwable $te) {
                // ignore
            }
            $typeCodeId = $typeModel ? $typeModel->id : 1;

            $ot = new Overtime();
            $ot->ot_maker_at = now();
            $ot->ot_maker_by = $makerBy;
            $ot->ot_maker_property = json_encode(['nama' => $u['name'], 'nik' => $u['nip'], 'jabatan' => $u['role']]);
            $ot->ot_approver_by = $approverBy;
            $ot->ot_maker_status = 'Waiting';
            $ot->ot_approver_status = 'Waiting';
            $ot->ot_start_at = $startDt->format('Y-m-d H:i:s');
            $ot->ot_end_at = $endDt->format('Y-m-d H:i:s');
            $ot->ot_length = $diffSec;
            $ot->ot_start_clockin = (clone $startDt)->subHour()->format('Y-m-d H:i:s');
            $ot->ot_end_clockin = $endDt->format('Y-m-d H:i:s');
            $ot->ot_start_clockout = $startDt->format('Y-m-d H:i:s');
            $ot->ot_end_clockout = (clone $endDt)->addHours(3)->format('Y-m-d H:i:s');
            $ot->ot_title = $title;
            $ot->ot_desc = $desc;
            $ot->ot_type_code = $typeCodeId;
            $ot->save();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => true, 'message' => 'Pengajuan lembur berhasil disimpan.']);
            }
            return redirect()->back()->with('msg', 'Data pengajuan lembur berhasil disimpan.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('submitPropose error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menyimpan pengajuan lembur.');
        }
    }

    /**
     * Profile page.
     */
    public function profile()
    {
        $u = $this->getUserContext();
        $nik = $u['nip'];
        $k = $u['karyawan'];

        $children = [];
        $educations = [];
        $trainings = [];
        $certifications = [];

        try {
            $children = KaryawanAnak::where('nik', $nik)->orderBy('id_anakke', 'asc')->get();
            $educations = KaryawanPendidikan::with('_jenjang')->where('nik', $nik)->orderBy('id', 'desc')->get();
            $trainings = KaryawanPelatihan::where('nik', $nik)->orderBy('id', 'desc')->get();
            $certifications = KaryawanSertifikasi::where('nik', $nik)->orderBy('id', 'desc')->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('submitModule error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return view('matta.profile', compact('u', 'k', 'children', 'educations', 'trainings', 'certifications'));
    }

    /**
     * Save Profile changes to mtr_tbl_karyawan.
     */
    public function updateProfile(Request $request)
    {
        $u = $this->getUserContext();
        $k = $u['karyawan'];
        if ($k && $k->exists) {
            try {
                if ($request->filled('full_name')) $k->nama = $request->input('full_name');
                if ($request->filled('email')) $k->email = $request->input('email');
                if ($request->filled('birth_place')) $k->tempat_lahir = $request->input('birth_place');
                if ($request->filled('birth_date')) $k->tanggal_lahir = $request->input('birth_date');
                if ($request->filled('birth')) $k->tanggal_lahir = $request->input('birth');
                if ($request->filled('phone1')) $k->telepon1 = $request->input('phone1');
                if ($request->filled('phone')) $k->telepon1 = $request->input('phone');
                if ($request->filled('phone2')) $k->telepon2 = $request->input('phone2');
                if ($request->filled('emergency_phone')) $k->telepon_darurat = $request->input('emergency_phone');
                if ($request->filled('ktp_address')) $k->alamat_ktp = $request->input('ktp_address');
                if ($request->filled('domicile_address')) $k->alamat_domisili = $request->input('domicile_address');
                if ($request->filled('address')) $k->alamat_domisili = $request->input('address');
                if ($request->filled('npwp_address')) $k->alamat_npwp = $request->input('npwp_address');
                if ($request->filled('ktp_no')) $k->no_ktp = $request->input('ktp_no');
                if ($request->filled('npwp_no')) $k->no_npwp = $request->input('npwp_no');
                if ($request->filled('bpjs_kes')) $k->no_bpjs = $request->input('bpjs_kes');
                if ($request->filled('bpjs_tk')) $k->no_bpjstk = $request->input('bpjs_tk');
                if ($request->filled('spouse_name')) $k->nama_pasangan = $request->input('spouse_name');
                if ($request->filled('bank_name')) $k->nama_bank = $request->input('bank_name');
                if ($request->filled('bank_account')) $k->no_rek = $request->input('bank_account');
                if ($request->filled('bank_holder')) $k->nama_rek = $request->input('bank_holder');
                if ($request->filled('blood_type')) $k->golongan_darah = $request->input('blood_type');
                if ($request->filled('gender')) {
                    $gender = $request->input('gender');
                    $k->id_kelamin = str_contains(strtolower($gender), 'perempuan') ? 2 : 1;
                }
                if ($request->filled('nationality')) {
                    $nat = $request->input('nationality');
                    $k->id_warga = str_contains(strtolower($nat), 'indonesia') ? 1 : 2;
                }
                $k->save();
            } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('submitModule error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Add / Update child row in mtr_tbl_karyawan_anak.
     */
    public function storeChild(Request $request)
    {
        $u = $this->getUserContext();
        try {
            $orderStr = $request->input('order', 'Anak ke-1');
            preg_match('/(\d+)/', $orderStr, $m);
            $orderNum = isset($m[1]) ? (int) $m[1] : 1;
            $childName = $request->input('child_name') ?: $request->input('name', 'Anak');

            if ($request->filled('id')) {
                $child = KaryawanAnak::where('nik', $u['nip'])->where('id', $request->input('id'))->first() ?? new KaryawanAnak();
            } else {
                $child = new KaryawanAnak();
            }

            $child->nik = $u['nip'];
            $child->id_anakke = $orderNum;
            $child->nama = $childName;
            $child->save();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('storeChild error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Delete child row in mtr_tbl_karyawan_anak.
     */
    public function deleteChild(Request $request)
    {
        $u = $this->getUserContext();
        try {
            $q = KaryawanAnak::where('nik', $u['nip']);
            if ($request->filled('id')) {
                $q->where('id', $request->input('id'));
            } elseif ($request->filled('name')) {
                $q->where('nama', $request->input('name'));
            }
            $q->delete();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('submitModule error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Render any of the 8 Form Module pages:
     * overtime, evaluation, leave, permit, exit-clearance, reimbursement, sick-leave, confirmation
     */
    public function formModule(string $module)
    {
        $u = $this->getUserContext();
        $modules = $this->getModulesData($u);
        if (!isset($modules[$module])) {
            $module = 'overtime';
        }
        $mod = $modules[$module];

        if (view()->exists('matta.forms.' . $module)) {
            return view('matta.forms.' . $module, compact('u', 'module', 'mod', 'modules'));
        }

        return view('matta.forms.module', compact('u', 'module', 'mod', 'modules'));
    }

    /**
     * Persist new form submission from Dashboard or Module page into the database.
     */
    public function submitModule(Request $request)
    {
        $u = $this->getUserContext();
        $nik = $u['nip'];
        $module = $request->input('_module', 'leave');
        $start = $request->input('start', date('Y-m-d'));
        $end = $request->input('end') ?: $start;
        $note = $request->input('note') ?: $request->input('reason') ?: 'Pengajuan via MATTA Portal';

        try {
            if ($module === 'leave') {
                $days = max(1, Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1);
                $c = new TrxCuti();
                $c->tipe_cuti = 1;
                $c->periode_cuti = date('Y');
                $c->nik_karyawan = $nik;
                $c->nik_atasan = $u['approver_nip'];
                $c->pic_pengganti = $request->input('pic') ?: $u['approver_nip'];
                $c->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role'], 'divisi' => $u['division'], 'dep' => $u['department'], 'sub_dep' => $u['sub_department']]);
                $c->atasan_property = json_encode(['nama' => $u['approver'], 'nik' => $u['approver_nip']]);
                $c->tanggal_mulai = $start;
                $c->tanggal_akhir = $end;
                $c->jumlah_hari = $days;
                $c->tanggal_cuti = $start;
                $c->alasan = $request->input('reason') ?: $note;
                $c->alamat = $request->input('address') ?: '-';
                $c->telepon = $request->input('phone') ?: '-';
                $c->keterangan = $note;
                $c->status_cuti = 1;
                $c->save();
            } elseif ($module === 'permit') {
                $p = new TrxIjin();
                $p->karyawan = $nik;
                $p->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                $p->atasan = $u['approver_nip'];
                $p->atasan_property = json_encode(['nama' => $u['approver'], 'nik' => $u['approver_nip']]);
                $p->tipe = 1;
                $p->tanggal = $start;
                $p->jam_awal = $request->input('jam_awal', '09:00');
                $p->jam_akhir = $request->input('jam_akhir', '11:00');
                $p->alasan = $note;
                $p->status = 1;
                $p->save();
            } elseif ($module === 'sick-leave') {
                $days = max(1, Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1);
                $s = new TrxSakit();
                $s->periode_sakit = date('Y');
                $s->nik_karyawan = $nik;
                $s->nik_atasan = $u['approver_nip'];
                $s->pic_pengganti = $request->input('pic') ?: $u['approver_nip'];
                $s->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                $s->atasan_property = json_encode(['nama' => $u['approver'], 'nik' => $u['approver_nip']]);
                $s->tanggal_mulai = $start;
                $s->tanggal_akhir = $end;
                $s->jumlah_hari = $days;
                $s->tanggal_sakit = $start;
                $s->alasan = $request->input('reason') ?: 'Sakit';
                $s->alamat = $request->input('address') ?: '-';
                $s->telepon = $request->input('phone') ?: '-';
                $s->keterangan = $note;
                $s->status_sakit = 1;
                $s->save();
            } elseif ($module === 'overtime') {
                $ot = new Overtime();
                $ot->ot_maker_at = now();
                $ot->ot_maker_by = $nik;
                $ot->ot_maker_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                $ot->ot_approver_by = $u['approver_nip'];
                $ot->ot_maker_status = 1;
                $ot->ot_approver_status = 0;
                $ot->ot_start_at = $start . ' 18:00:00';
                $ot->ot_end_at = $start . ' 20:00:00';
                $ot->ot_length = '02:00:00';
                $ot->ot_title = 'Formulir Lembur ' . $start;
                $ot->ot_desc = $note;
                $ot->ot_type_code = 1;
                $ot->save();
            } elseif ($module === 'reimbursement') {
                $ctrl = app(\App\Http\Controllers\TrxReimburseController::class);
                $prop = $ctrl->getNikProperty($nik);
                if (empty($prop) || $prop === 'null') {
                    $prop = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                }
                $claimAmount = (int) ($request->input('amount') ?: 350000);
                $rm = new TrxReimburseMaster();
                $rm->nik_karyawan = $nik;
                $rm->karyawan_property = $prop;
                $rm->periode = date('Y');
                $rm->plafon = 5000000;
                $rm->plafon_diambil = 0;
                $rm->sisa_plafon_1 = 5000000;
                $rm->klaim_kesehatan = $claimAmount;
                $rm->klaim_non_kesehatan = 0;
                $rm->sisa_plafon_2 = 5000000 - $claimAmount;
                $rm->status_pengajuan = 1;
                $rm->catatan = $note;
                $rm->save();

                $dt = new \App\Models\TrxReimburseDetail();
                $dt->id_reimburse = $rm->id;
                $dt->nama_pasien = $u['name'];
                $dt->id_hubungan = 1;
                $dt->id_layanan = 1;
                $dt->tanggal = $start ?: date('Y-m-d');
                $dt->jumlah_klaim = $claimAmount;
                $dt->keterangan = $note;
                $dt->save();
            } elseif ($module === 'exit-clearance') {
                $ex = new TrxNonaktifMaster();
                $ex->pengaju = $nik;
                $ex->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                $ex->atasan = $u['approver_nip'];
                $ex->status = 1;
                $ex->alasan = $note;
                $ex->save();
            } elseif ($module === 'evaluation') {
                $ev = new TrxPenilaian();
                $ev->nik_karyawan = $nik;
                $ev->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                $ev->id_status = 1;
                $ev->save();
            } elseif ($module === 'confirmation') {
                $cf = new TrxRekomendasiMaster();
                $cf->nik_karyawan = $nik;
                $cf->karyawan_property = json_encode(['nama' => $u['name'], 'nik' => $nik, 'jabatan' => $u['role']]);
                $cf->status_rekomendasi = 1;
                $cf->save();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('submitModule error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return response()->json(['ok' => true]);
    }
}

