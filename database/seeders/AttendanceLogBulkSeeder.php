<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceLogBulkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Memulai seeding data absensi & log (minimal 20.000 data per tabel utama)...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Ambil data karyawan master asli yang sudah ada di database (JANGAN DIHAPUS / TRUNCATE)
        $karyawanList = DB::table('mtr_tbl_karyawan')
            ->select('id', 'nik', 'nama', 'wg_id', 'id_fp', 'kode_perusahaan', 'kode_divisi', 'kode_departemen', 'kode_jabatan', 'kode_level', 'email')
            ->get();

        if ($karyawanList->isEmpty()) {
            $this->command->error('Tabel mtr_tbl_karyawan kosong! Harap pastikan data karyawan ada.');
            return;
        }

        $totalKaryawan = $karyawanList->count();
        $targetCount = 20000;
        $batchSize = 1000;

        // Siapkan lookup sederhana untuk format JSON property
        $divisiMap = DB::table('mtr_tbl_divisi')->pluck('nama', 'id')->toArray();
        $deptMap = DB::table('mtr_tbl_departemen')->pluck('nama', 'id')->toArray();
        $jabatanMap = DB::table('mtr_tbl_jabatan')->pluck('jabatan', 'id')->toArray();
        $levelMap = DB::table('mtr_tbl_level')->pluck('level', 'id')->toArray();
        $perusahaanMap = DB::table('mtr_tbl_perusahaan')->pluck('nama', 'id')->toArray();

        // 1. TRX_TBL_ATTENDANCE (20.000 data)
        $currentAttCount = DB::table('trx_tbl_attendance')->count();
        if ($currentAttCount < $targetCount) {
            $needed = $targetCount - $currentAttCount;
            $this->command->info("Menambahkan {$needed} data ke trx_tbl_attendance...");
            $attBatch = [];

            for ($i = 1; $i <= $needed; $i++) {
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $dayOffset = ($i % 90);
                $dateObj = Carbon::now()->subDays($dayOffset);
                $isClockIn = ($i % 2 === 1);
                
                $timeHour = $isClockIn ? rand(7, 9) : rand(17, 20);
                $timeMin = rand(0, 59);
                $timeSec = rand(0, 59);
                $timestamp = (clone $dateObj)->setTime($timeHour, $timeMin, $timeSec);
                $attType = $isClockIn ? 'Clock-in' : 'Clock-out';
                $attStatus = $isClockIn ? ($timeHour > 8 || ($timeHour == 8 && $timeMin > 30) ? 'Late-in' : 'Book-in') : 'Book-out';

                $prop = json_encode([
                    'nik' => $karyawan->nik,
                    'nama' => $karyawan->nama,
                    'email' => $karyawan->email ?: ($karyawan->nik . '@alita.id'),
                    'telepon1' => '+62812' . rand(10000000, 99999999),
                    'perusahaan' => $perusahaanMap[$karyawan->kode_perusahaan] ?? 'PT ALITA PRAYA MITRA',
                    'divisi' => $divisiMap[$karyawan->kode_divisi] ?? 'Direktorat Operasional',
                    'dep' => $deptMap[$karyawan->kode_departemen] ?? 'Divisi IT',
                    'jabatan' => $jabatanMap[$karyawan->kode_jabatan] ?? 'Staff IT',
                    'level' => $levelMap[$karyawan->kode_level] ?? 'Staff',
                ]);

                $attBatch[] = [
                    'att_date' => $dateObj->format('Y-m-d'),
                    'att_timestamp' => $timestamp->format('Y-m-d H:i:s'),
                    'att_nik' => $karyawan->nik,
                    'att_type' => $attType,
                    'att_long' => 106.8227 + (rand(-100, 100) / 10000.0),
                    'att_lat' => -6.2088 + (rand(-100, 100) / 10000.0),
                    'att_address' => 'Gedung Alita, Jl. TB Simatupang Kav. 88, Cilandak, Jakarta Selatan',
                    'att_ref_long' => 106.8227,
                    'att_ref_lat' => -6.2088,
                    'att_ref_address' => 'Head Office Alita, Jakarta Selatan',
                    'att_distance_gap' => rand(1, 50) / 1000.0,
                    'att_clock_gap' => sprintf('00:%02d:00', rand(0, 30)),
                    'att_status' => $attStatus,
                    'att_data_src' => ($i % 5 === 0) ? 'Overtime' : 'Regular',
                    'att_wd_ot_id' => 0,
                    'att_property' => $prop,
                    'wg_id' => $karyawan->wg_id ?: 1,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                    'id_fp_trx' => $karyawan->id_fp ?: $karyawan->id,
                ];

                if (count($attBatch) >= $batchSize) {
                    DB::table('trx_tbl_attendance')->insert($attBatch);
                    $attBatch = [];
                }
            }

            if (!empty($attBatch)) {
                DB::table('trx_tbl_attendance')->insert($attBatch);
            }
            $this->command->info("Selesai trx_tbl_attendance: total " . DB::table('trx_tbl_attendance')->count() . " rows.");
        }

        // 2. TRX_TBL_ATTENDANCE_LOG (20.000 data)
        $currentLogCount = DB::table('trx_tbl_attendance_log')->count();
        if ($currentLogCount < $targetCount) {
            $needed = $targetCount - $currentLogCount;
            $this->command->info("Menambahkan {$needed} data ke trx_tbl_attendance_log...");
            $logBatch = [];

            for ($i = 1; $i <= $needed; $i++) {
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $dayOffset = ($i % 90);
                $dateObj = Carbon::now()->subDays($dayOffset);
                $isClockIn = ($i % 2 === 1);
                $timeHour = $isClockIn ? rand(7, 9) : rand(17, 20);
                $timeMin = rand(0, 59);
                $timeSec = rand(0, 59);
                $timestamp = (clone $dateObj)->setTime($timeHour, $timeMin, $timeSec);
                $attType = $isClockIn ? 'Clock-in' : 'Clock-out';
                $attStatus = $isClockIn ? ($timeHour > 8 || ($timeHour == 8 && $timeMin > 30) ? 'Late-in' : 'Book-in') : 'Book-out';

                $prop = json_encode([
                    'nik' => $karyawan->nik,
                    'nama' => $karyawan->nama,
                    'email' => $karyawan->email ?: ($karyawan->nik . '@alita.id'),
                    'telepon1' => '+62812' . rand(10000000, 99999999),
                    'perusahaan' => $perusahaanMap[$karyawan->kode_perusahaan] ?? 'PT ALITA PRAYA MITRA',
                    'divisi' => $divisiMap[$karyawan->kode_divisi] ?? 'Direktorat Operasional',
                    'dep' => $deptMap[$karyawan->kode_departemen] ?? 'Divisi IT',
                    'jabatan' => $jabatanMap[$karyawan->kode_jabatan] ?? 'Staff IT',
                    'level' => $levelMap[$karyawan->kode_level] ?? 'Staff',
                ]);

                $logBatch[] = [
                    'att_date' => $dateObj->format('Y-m-d'),
                    'att_timestamp' => $timestamp->format('Y-m-d H:i:s'),
                    'att_nik' => $karyawan->nik,
                    'att_type' => $attType,
                    'att_long' => 106.8227 + (rand(-100, 100) / 10000.0),
                    'att_lat' => -6.2088 + (rand(-100, 100) / 10000.0),
                    'att_address' => 'Gedung Alita, Jl. TB Simatupang Kav. 88, Cilandak, Jakarta Selatan',
                    'att_ref_long' => 106.8227,
                    'att_ref_lat' => -6.2088,
                    'att_ref_address' => 'Head Office Alita, Jakarta Selatan',
                    'att_distance_gap' => rand(1, 50) / 1000.0,
                    'att_clock_gap' => sprintf('00:%02d:00', rand(0, 30)),
                    'att_status' => $attStatus,
                    'att_data_src' => ($i % 5 === 0) ? 'Overtime' : 'Regular',
                    'att_wd_ot_id' => 0,
                    'att_property' => $prop,
                    'wg_id' => $karyawan->wg_id ?: 1,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                if (count($logBatch) >= $batchSize) {
                    DB::table('trx_tbl_attendance_log')->insert($logBatch);
                    $logBatch = [];
                }
            }

            if (!empty($logBatch)) {
                DB::table('trx_tbl_attendance_log')->insert($logBatch);
            }
            $this->command->info("Selesai trx_tbl_attendance_log: total " . DB::table('trx_tbl_attendance_log')->count() . " rows.");
        }

        // 3. TRX_TBL_FP_DATA (20.000 data)
        $currentFpCount = DB::table('trx_tbl_fp_data')->count();
        if ($currentFpCount < $targetCount) {
            $needed = $targetCount - $currentFpCount;
            $this->command->info("Menambahkan {$needed} data ke trx_tbl_fp_data...");
            $fpBatch = [];

            for ($i = 1; $i <= $needed; $i++) {
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $dayOffset = ($i % 90);
                $dateObj = Carbon::now()->subDays($dayOffset);
                $fpTime = (clone $dateObj)->setTime(rand(7, 20), rand(0, 59), rand(0, 59));

                $fpBatch[] = [
                    'id_data' => $currentFpCount + $i,
                    'id_nip' => $karyawan->id_fp ?: $karyawan->id,
                    'is_state' => rand(1, 4),
                    'waktu' => $fpTime->format('Y-m-d H:i:s'),
                    'is_type' => ($i % 2 === 0) ? 0 : 1,
                    'created_at' => $fpTime,
                    'updated_at' => $fpTime,
                    'id_mesin' => ($i % 2 === 0) ? 1 : 2,
                ];

                if (count($fpBatch) >= $batchSize) {
                    DB::table('trx_tbl_fp_data')->insert($fpBatch);
                    $fpBatch = [];
                }
            }

            if (!empty($fpBatch)) {
                DB::table('trx_tbl_fp_data')->insert($fpBatch);
            }
            $this->command->info("Selesai trx_tbl_fp_data: total " . DB::table('trx_tbl_fp_data')->count() . " rows.");
        }

        // 4. TRX_TBL_FP_DATA_MATTA (20.000 data)
        $currentFpMattaCount = DB::table('trx_tbl_fp_data_matta')->count();
        if ($currentFpMattaCount < $targetCount) {
            $needed = $targetCount - $currentFpMattaCount;
            $this->command->info("Menambahkan {$needed} data ke trx_tbl_fp_data_matta...");
            $fpMattaBatch = [];

            for ($i = 1; $i <= $needed; $i++) {
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $dayOffset = ($i % 90);
                $dateObj = Carbon::now()->subDays($dayOffset);
                $isClockIn = ($i % 2 === 1);
                $timeHour = $isClockIn ? rand(7, 9) : rand(17, 20);
                $timeMin = rand(0, 59);
                $timeSec = rand(0, 59);
                $timestamp = (clone $dateObj)->setTime($timeHour, $timeMin, $timeSec);
                $attType = $isClockIn ? 'Clock-in' : 'Clock-out';
                $attStatus = $isClockIn ? ($timeHour > 8 || ($timeHour == 8 && $timeMin > 30) ? 'Late-in' : 'Book-in') : 'Book-out';

                $prop = json_encode([
                    'nik' => $karyawan->nik,
                    'nama' => $karyawan->nama,
                    'email' => $karyawan->email ?: ($karyawan->nik . '@alita.id'),
                    'telepon1' => '+62812' . rand(10000000, 99999999),
                    'perusahaan' => $perusahaanMap[$karyawan->kode_perusahaan] ?? 'PT ALITA PRAYA MITRA',
                    'divisi' => $divisiMap[$karyawan->kode_divisi] ?? 'Direktorat Operasional',
                    'dep' => $deptMap[$karyawan->kode_departemen] ?? 'Divisi IT',
                    'jabatan' => $jabatanMap[$karyawan->kode_jabatan] ?? 'Staff IT',
                    'level' => $levelMap[$karyawan->kode_level] ?? 'Staff',
                ]);

                $fpMattaBatch[] = [
                    'att_date' => $dateObj->format('Y-m-d'),
                    'att_timestamp' => $timestamp->format('Y-m-d H:i:s'),
                    'att_nik' => $karyawan->nik,
                    'att_type' => $attType,
                    'att_long' => 106.8227 + (rand(-100, 100) / 10000.0),
                    'att_lat' => -6.2088 + (rand(-100, 100) / 10000.0),
                    'att_address' => 'Gedung Alita, Jl. TB Simatupang Kav. 88, Cilandak, Jakarta Selatan',
                    'att_ref_long' => 106.8227,
                    'att_ref_lat' => -6.2088,
                    'att_ref_address' => 'Head Office Alita, Jakarta Selatan',
                    'att_distance_gap' => rand(1, 50) / 1000.0,
                    'att_clock_gap' => sprintf('00:%02d:00', rand(0, 30)),
                    'att_status' => $attStatus,
                    'att_data_src' => 'Fingerprint Mesin',
                    'att_wd_ot_id' => 0,
                    'att_property' => $prop,
                    'wg_id' => $karyawan->wg_id ?: 1,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                if (count($fpMattaBatch) >= $batchSize) {
                    DB::table('trx_tbl_fp_data_matta')->insert($fpMattaBatch);
                    $fpMattaBatch = [];
                }
            }

            if (!empty($fpMattaBatch)) {
                DB::table('trx_tbl_fp_data_matta')->insert($fpMattaBatch);
            }
            $this->command->info("Selesai trx_tbl_fp_data_matta: total " . DB::table('trx_tbl_fp_data_matta')->count() . " rows.");
        }

        // 5. TRX_TBL_WORKING_DAY (20.000 data)
        $currentWdCount = DB::table('trx_tbl_working_day')->count();
        if ($currentWdCount < $targetCount) {
            $needed = $targetCount - $currentWdCount;
            $this->command->info("Menambahkan {$needed} data ke trx_tbl_working_day...");
            $wdBatch = [];

            for ($i = 1; $i <= $needed; $i++) {
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $dayOffset = ($i % 90);
                $d = Carbon::now()->subDays($dayOffset);

                $wdBatch[] = [
                    'wg_id' => $karyawan->wg_id ?: 1,
                    'shift' => 1,
                    'nik' => $karyawan->nik,
                    'working_date' => $d->format('Y-m-d'),
                    'clockin' => (clone $d)->setTime(8, 30, 0)->format('Y-m-d H:i:s'),
                    'clockout' => (clone $d)->setTime(17, 30, 0)->format('Y-m-d H:i:s'),
                    'start_clockin' => (clone $d)->setTime(7, 30, 0)->format('Y-m-d H:i:s'),
                    'end_clockin' => (clone $d)->setTime(15, 0, 0)->format('Y-m-d H:i:s'),
                    'start_clockout' => (clone $d)->setTime(16, 30, 0)->format('Y-m-d H:i:s'),
                    'end_clockout' => (clone $d)->setTime(23, 59, 59)->format('Y-m-d H:i:s'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                if (count($wdBatch) >= $batchSize) {
                    DB::table('trx_tbl_working_day')->insert($wdBatch);
                    $wdBatch = [];
                }
            }

            if (!empty($wdBatch)) {
                DB::table('trx_tbl_working_day')->insert($wdBatch);
            }
            $this->command->info("Selesai trx_tbl_working_day: total " . DB::table('trx_tbl_working_day')->count() . " rows.");
        }

        // 6. ACTIVITY_LOG (20.000 data)
        $currentActCount = DB::table('activity_log')->count();
        if ($currentActCount < $targetCount) {
            $needed = $targetCount - $currentActCount;
            $this->command->info("Menambahkan {$needed} data ke activity_log...");
            $actBatch = [];

            $events = ['created', 'updated', 'submitted', 'approved', 'downloaded'];
            $descriptions = [
                'Melakukan clock-in absensi mobile',
                'Melakukan clock-out absensi mobile',
                'Mengajukan permohonan cuti reguler',
                'Mengajukan klaim reimbursement kesehatan',
                'Mengajukan lembur kerja lapangan',
                'Menyetujui permohonan bawahan',
                'Mengunduh laporan kehadiran bulanan',
                'Mengisi evaluasi penilaian kinerja'
            ];

            for ($i = 1; $i <= $needed; $i++) {
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $dayOffset = ($i % 90);
                $timestamp = Carbon::now()->subDays($dayOffset)->setTime(rand(7, 21), rand(0, 59), rand(0, 59));
                $ev = $events[rand(0, count($events) - 1)];
                $desc = $descriptions[rand(0, count($descriptions) - 1)];

                $actBatch[] = [
                    'log_name' => 'attendance_activity',
                    'description' => $desc,
                    'subject_type' => 'App\\Models\\Attendance',
                    'event' => $ev,
                    'subject_id' => $i,
                    'causer_type' => 'App\\Models\\User',
                    'causer_id' => $karyawan->id,
                    'properties' => json_encode([
                        'nik' => $karyawan->nik,
                        'nama' => $karyawan->nama,
                        'ip' => '192.168.10.' . (($i % 250) + 1),
                        'user_agent' => 'MATTA-Mobile-Client/2.4 (Android 14; Build/UP1A)'
                    ]),
                    'batch_uuid' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                if (count($actBatch) >= $batchSize) {
                    DB::table('activity_log')->insert($actBatch);
                    $actBatch = [];
                }
            }

            if (!empty($actBatch)) {
                DB::table('activity_log')->insert($actBatch);
            }
            $this->command->info("Selesai activity_log: total " . DB::table('activity_log')->count() . " rows.");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Sukses! Seeding AttendanceLogBulkSeeder selesai.');
    }
}
