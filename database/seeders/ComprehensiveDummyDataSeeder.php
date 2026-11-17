<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprehensiveDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('Memulai ComprehensiveDummyDataSeeder (tanpa menghapus data yang sudah ada)...');

        // Helper insert dalam chunk kecil untuk mencegah max_allowed_packet exceeded
        $safeInsert = function ($table, array $rows, $chunk = 50) {
            if (empty($rows)) return;
            foreach (array_chunk($rows, $chunk) as $batch) {
                DB::table($table)->insert($batch);
            }
        };

        // 1. Ambil data referensi karyawan dari database yang sudah ada
        $karyawanList = DB::table('mtr_tbl_karyawan')->get();
        if ($karyawanList->isEmpty()) {
            $this->command->error('Tabel mtr_tbl_karyawan kosong!');
            return;
        }

        $totalKaryawan = $karyawanList->count();
        $this->command->info("Menggunakan {$totalKaryawan} data karyawan yang ada di database.");

        // Lookup maps
        $divisiMap = DB::table('mtr_tbl_divisi')->pluck('nama', 'id')->toArray();
        $deptMap = DB::table('mtr_tbl_departemen')->pluck('nama', 'id')->toArray();
        $subdeptMap = DB::table('mtr_tbl_sub_departemen')->pluck('nama', 'id')->toArray();
        $jabatanMap = DB::table('mtr_tbl_jabatan')->pluck('jabatan', 'id')->toArray();
        $levelMap = DB::table('mtr_tbl_level')->pluck('level', 'id')->toArray();
        $perusahaanMap = DB::table('mtr_tbl_perusahaan')->pluck('nama', 'id')->toArray();

        $formatProp = function ($k) use ($perusahaanMap, $divisiMap, $deptMap, $subdeptMap, $jabatanMap, $levelMap) {
            return json_encode([
                'nik' => $k->nik,
                'nama' => $k->nama,
                'email' => $k->email ?: ($k->nik . '@alita.id'),
                'telepon1' => '+62812' . rand(10000000, 99999999),
                'perusahaan' => $perusahaanMap[$k->kode_perusahaan] ?? 'PT ALITA PRAYA MITRA',
                'divisi' => $divisiMap[$k->kode_divisi] ?? 'Direktorat Operasional',
                'dep' => $deptMap[$k->kode_departemen] ?? 'Divisi IT',
                'sub_dep' => $subdeptMap[$k->kode_sub_departemen] ?? 'Departemen Aplikasi',
                'jabatan' => $jabatanMap[$k->kode_jabatan] ?? 'Staff IT',
                'level' => $levelMap[$k->kode_level] ?? 'Staff',
                'id_level' => $k->kode_level ?: 1,
                'status_karyawan' => 'Tetap',
                'findim_divisi' => 'DIV-IT',
                'findim_departemen' => 'DEP-DEV',
                'findim_subdepartemen' => 'SUB-APP',
                'findim_project' => 'PRJ-MATTA',
                'tanggal_bergabung' => '2023-01-01',
                'tanggal_berakhir' => '2026-12-31'
            ]);
        };

        $totalTx = 1000;

        // -------------------------------------------------------------
        // A. EMPTY MASTER / SCHEDULE TABLES (JIKA MASIH KOSONG)
        // -------------------------------------------------------------
        if (DB::table('mtr_tbl_jadwal_noc')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 100; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $batch[] = [
                    'nik' => $k->nik,
                    'id_shift' => rand(1, 3),
                    'tanggal_awal' => Carbon::now()->subDays(30)->format('Y-m-d'),
                    'tanggal_akhir' => Carbon::now()->addDays(30)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $safeInsert('mtr_tbl_jadwal_noc', $batch, 50);
        }

        if (DB::table('mtr_tbl_jadwal_qats')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 100; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $batch[] = [
                    'nik' => $k->nik,
                    'id_shift' => rand(1, 3),
                    'tanggal_awal' => Carbon::now()->subDays(30)->format('Y-m-d'),
                    'tanggal_akhir' => Carbon::now()->addDays(30)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $safeInsert('mtr_tbl_jadwal_qats', $batch, 50);
        }

        if (DB::table('mtr_tbl_jadwal_qats_leader')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 50; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $batch[] = [
                    'nik' => $k->nik,
                    'id_shift' => rand(1, 3),
                    'tanggal_awal' => Carbon::now()->subDays(30)->format('Y-m-d'),
                    'tanggal_akhir' => Carbon::now()->addDays(30)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $safeInsert('mtr_tbl_jadwal_qats_leader', $batch, 50);
        }

        if (DB::table('mtr_tbl_jadwal_random')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 50; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $batch[] = [
                    'nik' => $k->nik,
                    'id_shift' => rand(1, 2),
                    'tanggal_awal' => Carbon::now()->subDays(30)->format('Y-m-d'),
                    'tanggal_akhir' => Carbon::now()->addDays(30)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $safeInsert('mtr_tbl_jadwal_random', $batch, 50);
        }

        if (DB::table('mtr_tbl_jadwal_servicedesk')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 50; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $batch[] = [
                    'nik' => $k->nik,
                    'id_shift' => rand(1, 2),
                    'tanggal_awal' => Carbon::now()->subDays(30)->format('Y-m-d'),
                    'tanggal_akhir' => Carbon::now()->addDays(30)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $safeInsert('mtr_tbl_jadwal_servicedesk', $batch, 50);
        }

        if (DB::table('mtr_tbl_karyawan_bpjs')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 200; $i++) {
                $batch[] = [
                    'id_data' => $i,
                    'bpjs_tk' => '000' . rand(10000000, 99999999),
                    'bpjs_kes' => '000' . rand(10000000, 99999999)
                ];
            }
            $safeInsert('mtr_tbl_karyawan_bpjs', $batch, 50);
        }

        if (DB::table('mtr_tbl_karyawan_fp')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= min(500, $totalKaryawan); $i++) {
                $k = $karyawanList[$i - 1];
                $batch[] = [
                    'id' => $i,
                    'nik' => $k->nik,
                    'nama' => $k->nama
                ];
            }
            $safeInsert('mtr_tbl_karyawan_fp', $batch, 50);
        }

        if (DB::table('mtr_tbl_karyawan_bulanan')->count() == 0) {
            $batch = [];
            for ($i = 1; $i <= 200; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $batch[] = [
                    'tanggal' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'layer1' => $k->layer1 ?: 'L1-001',
                    'layer1_property' => $formatProp($k),
                    'layer2' => $k->layer2 ?: 'L2-001',
                    'layer2_property' => $formatProp($k),
                    'perubahan' => 'Penyesuaian Data Bulanan',
                    'is_berubah' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $safeInsert('mtr_tbl_karyawan_bulanan', $batch, 50);
        }

        if (DB::table('mtr_tbl_bpd_coa_bkp')->count() == 0) {
            $coas = DB::table('mtr_tbl_bpd_coa')->get();
            if ($coas->isNotEmpty()) {
                $batch = [];
                foreach ($coas as $c) {
                    $batch[] = [
                        'account' => $c->account,
                        'name' => $c->name,
                        'type' => $c->type,
                        'category' => $c->category,
                        'divisi' => $c->divisi,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                $safeInsert('mtr_tbl_bpd_coa_bkp', $batch, 50);
            }
        }

        // -------------------------------------------------------------
        // 1. CUTI (trx_tbl_cuti & trx_tbl_cuti_log)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_cuti')->count() < $totalTx) {
            $this->command->info('Membuat data Cuti...');
            $cutiTipeIds = DB::table('mtr_tbl_cuti_tipe')->pluck('id')->toArray() ?: [1, 2];
            $cutiStatusIds = DB::table('mtr_tbl_cuti_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $startId = (int) DB::table('trx_tbl_cuti')->max('id');
            $cutiBatch = [];
            $cutiLogBatch = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 3) % $totalKaryawan];
                $pic = $karyawanList[($i * 7) % $totalKaryawan];

                $startDate = Carbon::now()->subDays(rand(1, 90));
                $days = rand(1, 5);
                $endDate = (clone $startDate)->addDays($days - 1);
                $tipeId = $cutiTipeIds[array_rand($cutiTipeIds)];
                $statusId = $cutiStatusIds[array_rand($cutiStatusIds)];

                $cutiBatch[] = [
                    'id' => $txId,
                    'tipe_cuti' => $tipeId,
                    'periode_cuti' => $startDate->year,
                    'nik_karyawan' => $karyawan->nik,
                    'nik_atasan' => $atasan->nik,
                    'pic_pengganti' => $pic->nik,
                    'karyawan_property' => $formatProp($karyawan),
                    'atasan_property' => $formatProp($atasan),
                    'pic_property' => $formatProp($pic),
                    'tanggal_mulai' => $startDate->format('Y-m-d'),
                    'tanggal_akhir' => $endDate->format('Y-m-d'),
                    'jumlah_hari' => $days,
                    'tanggal_cuti' => $startDate->format('Y-m-d'),
                    'alasan' => 'Keperluan keluarga dan cuti tahunan',
                    'alamat' => 'Jakarta Selatan',
                    'telepon' => '0812' . rand(10000000, 99999999),
                    'keterangan' => 'Pengajuan cuti online MATTA',
                    'status_cuti' => $statusId,
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $cutiLogBatch[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Status pengajuan cuti diproses',
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];
            }
            $safeInsert('trx_tbl_cuti', $cutiBatch, 50);
            $safeInsert('trx_tbl_cuti_log', $cutiLogBatch, 50);
        }

        // -------------------------------------------------------------
        // 2. SAKIT (trx_tbl_sakit & trx_tbl_sakit_log)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_sakit')->count() < $totalTx) {
            $this->command->info('Membuat data Sakit...');
            $sakitStatusIds = DB::table('mtr_tbl_sakit_status')->pluck('id')->toArray() ?: [1, 4, 6];
            $startId = (int) DB::table('trx_tbl_sakit')->max('id');
            $sakitBatch = [];
            $sakitLogBatch = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 2) % $totalKaryawan];
                $pic = $karyawanList[($i * 5) % $totalKaryawan];

                $startDate = Carbon::now()->subDays(rand(1, 90));
                $days = rand(1, 3);
                $endDate = (clone $startDate)->addDays($days - 1);
                $statusId = $sakitStatusIds[array_rand($sakitStatusIds)];

                $sakitBatch[] = [
                    'id' => $txId,
                    'periode_sakit' => $startDate->year,
                    'nik_karyawan' => $karyawan->nik,
                    'nik_atasan' => $atasan->nik,
                    'pic_pengganti' => $pic->nik,
                    'karyawan_property' => $formatProp($karyawan),
                    'atasan_property' => $formatProp($atasan),
                    'pic_property' => $formatProp($pic),
                    'tanggal_mulai' => $startDate->format('Y-m-d'),
                    'tanggal_akhir' => $endDate->format('Y-m-d'),
                    'jumlah_hari' => $days,
                    'tanggal_sakit' => $startDate->format('Y-m-d'),
                    'alasan' => 'Demam dan flu berat, istirahat dokter',
                    'alamat' => 'Jakarta Barat',
                    'telepon' => '0812' . rand(10000000, 99999999),
                    'keterangan' => 'Surat keterangan dokter terlampir',
                    'lampiran' => 'surat_dokter_' . $i . '.pdf',
                    'status_sakit' => $statusId,
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $sakitLogBatch[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Surat sakit telah diverifikasi',
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];
            }
            $safeInsert('trx_tbl_sakit', $sakitBatch, 50);
            $safeInsert('trx_tbl_sakit_log', $sakitLogBatch, 50);
        }

        // -------------------------------------------------------------
        // 3. IJIN (trx_tbl_ijin & trx_tbl_ijin_log)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_ijin')->count() < $totalTx) {
            $this->command->info('Membuat data Ijin...');
            $ijinTipeIds = DB::table('mtr_tbl_ijin_tipe')->pluck('id')->toArray() ?: [1, 2, 3];
            $ijinStatusIds = DB::table('mtr_tbl_ijin_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $startId = (int) DB::table('trx_tbl_ijin')->max('id');
            $ijinBatch = [];
            $ijinLogBatch = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 4) % $totalKaryawan];
                $date = Carbon::now()->subDays(rand(1, 90));
                $tipeId = $ijinTipeIds[array_rand($ijinTipeIds)];
                $statusId = $ijinStatusIds[array_rand($ijinStatusIds)];

                $ijinBatch[] = [
                    'id' => $txId,
                    'karyawan' => $karyawan->nik,
                    'karyawan_property' => $formatProp($karyawan),
                    'atasan' => $atasan->nik,
                    'atasan_property' => $formatProp($atasan),
                    'layer2' => $atasan->nik,
                    'layer2_property' => $formatProp($atasan),
                    'tipe' => $tipeId,
                    'tanggal' => $date->format('Y-m-d'),
                    'jam_awal' => '08:30:00',
                    'jam_akhir' => '12:30:00',
                    'alasan' => 'Urusan administrasi penting keluarga',
                    'status' => $statusId,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];

                $ijinLogBatch[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Pengajuan izin telah disetujui atasan',
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
            $safeInsert('trx_tbl_ijin', $ijinBatch, 50);
            $safeInsert('trx_tbl_ijin_log', $ijinLogBatch, 50);
        }

        // -------------------------------------------------------------
        // 4. LEMBUR / OVERTIME (trx_tbl_overtime, log, copy, history)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_overtime')->count() < $totalTx) {
            $this->command->info('Membuat data Overtime / Lembur...');
            $otTypeCodes = DB::table('mtr_tbl_overtime_type')->pluck('id')->toArray() ?: [1, 2];
            $startId = (int) DB::table('trx_tbl_overtime')->max('id');
            $otBatch = [];
            $otLogBatch = [];
            $otCopyBatch = [];
            $otHistBatch = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 3) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));
                $startAt = (clone $d)->setTime(18, 0, 0);
                $endAt = (clone $d)->setTime(22, 0, 0);
                $otType = $otTypeCodes[array_rand($otTypeCodes)];

                $row = [
                    'id' => $txId,
                    'ot_maker_at' => $startAt->format('Y-m-d H:i:s'),
                    'ot_approver_at' => $endAt->format('Y-m-d H:i:s'),
                    'ot_maker_by' => $karyawan->nik,
                    'ot_maker_property' => substr($formatProp($karyawan), 0, 490),
                    'ot_freelance_property' => null,
                    'ot_approver_by' => $atasan->nik,
                    'ot_approver_property' => substr($formatProp($atasan), 0, 490),
                    'ot_maker_status' => 'Approved',
                    'ot_approver_status' => 'Approved',
                    'ot_start_at' => $startAt->format('Y-m-d H:i:s'),
                    'ot_end_at' => $endAt->format('Y-m-d H:i:s'),
                    'ot_length' => 4,
                    'ot_start_clockin' => $startAt->format('Y-m-d H:i:s'),
                    'ot_end_clockin' => (clone $startAt)->addHour()->format('Y-m-d H:i:s'),
                    'ot_start_clockout' => $endAt->format('Y-m-d H:i:s'),
                    'ot_end_clockout' => (clone $endAt)->addHour()->format('Y-m-d H:i:s'),
                    'ot_title' => 'Maintenance dan Deployment Sistem Produksi #' . $txId,
                    'ot_desc' => 'Dukungan pekerjaan overtime deployment aplikasi MATTA dan integrasi server.',
                    'ot_type_code' => $otType,
                    'ot_start_at_real' => $startAt->format('Y-m-d H:i:s'),
                    'ot_end_at_real' => $endAt->format('Y-m-d H:i:s'),
                    'ot_length_real' => '04:00:00',
                    'ot_start_at_rev' => null,
                    'ot_end_at_rev' => null,
                    'ot_length_rev' => null,
                    'ot_length_adj' => 4,
                    'ot_length_rp' => 150000,
                    'ot_length_rp_adj' => 150000,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $otBatch[] = $row;
                $otCopyBatch[] = $row;

                $otHistRow = $row;
                $otHistRow['id_ot'] = $txId;
                $otHistRow['ot_edit_by'] = $atasan->nik;
                $otHistRow['ot_edit_property'] = substr($formatProp($atasan), 0, 490);
                $otHistBatch[] = $otHistRow;

                $otLogBatch[] = [
                    'id' => $txId,
                    'id_ot' => $txId,
                    'ot_approver_status' => 'Approved',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }
            $safeInsert('trx_tbl_overtime', $otBatch, 50);
            $safeInsert('trx_tbl_overtime_copy', $otCopyBatch, 50);
            $safeInsert('trx_tbl_overtime_history', $otHistBatch, 50);
            $safeInsert('trx_tbl_overtime_log', $otLogBatch, 50);
        }

        // -------------------------------------------------------------
        // 5. REIMBURSE (trx_tbl_reimburse_master, detail, log, lampiran)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_reimburse_master')->count() < $totalTx) {
            $this->command->info('Membuat data Reimburse...');
            $layananIds = DB::table('mtr_tbl_reimburse_layanan')->pluck('id')->toArray() ?: [1, 2];
            $statusIds = DB::table('mtr_tbl_reimburse_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $hubunganIds = DB::table('mtr_tbl_reimburse_hubungan')->pluck('id')->toArray() ?: [1, 2];
            $startId = (int) DB::table('trx_tbl_reimburse_master')->max('id');

            $reimMaster = [];
            $reimDetail = [];
            $reimLog = [];
            $reimLamp = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $karyawan = $karyawanList[($i - 1) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));
                $nominal = rand(200, 1500) * 1000;
                $statusId = $statusIds[array_rand($statusIds)];

                $reimMaster[] = [
                    'id' => $txId,
                    'nik_karyawan' => $karyawan->nik,
                    'karyawan_property' => $formatProp($karyawan),
                    'periode' => $d->year,
                    'plafon' => 10000000,
                    'plafon_diambil' => $nominal,
                    'sisa_plafon_1' => 10000000 - $nominal,
                    'klaim_kesehatan' => $nominal,
                    'klaim_non_kesehatan' => 0,
                    'sisa_plafon_2' => 10000000 - $nominal,
                    'status_pengajuan' => $statusId,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $reimDetail[] = [
                    'id' => $txId,
                    'id_reimburse' => $txId,
                    'nama_pasien' => $karyawan->nama,
                    'id_hubungan' => $hubunganIds[array_rand($hubunganIds)],
                    'id_layanan' => $layananIds[array_rand($layananIds)],
                    'tanggal' => $d->format('Y-m-d'),
                    'jumlah_klaim' => $nominal,
                    'keterangan' => 'Kuitansi pengobatan rawat jalan',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $reimLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $karyawan->nik,
                    'keterangan' => 'Pengajuan klaim berhasil disubmit',
                    'created_at' => $d,
                    'updated_at' => $d,
                    'filename' => 'kwitansi_' . $txId . '.pdf',
                ];

                $reimLamp[] = [
                    'id' => $txId,
                    'id_reimburse' => $txId,
                    'id_lampiran' => 1,
                    'filename' => 'struk_' . $txId . '.pdf',
                    'keterangan' => 'Struk pembayaran apotek resmi',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }
            $safeInsert('trx_tbl_reimburse_master', $reimMaster, 50);
            $safeInsert('trx_tbl_reimburse_detail', $reimDetail, 50);
            $safeInsert('trx_tbl_reimburse_log', $reimLog, 50);
            $safeInsert('trx_tbl_reimburse_lampiran', $reimLamp, 50);
        }

        // -------------------------------------------------------------
        // 6. BPD / PERJALANAN DINAS (trx_tbl_bpd_master, info, biaya, log)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_bpd_master')->count() < $totalTx) {
            $this->command->info('Membuat data Perjalanan Dinas (BPD)...');
            $bpdStatusIds = DB::table('mtr_tbl_bpd_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $bpdProjects = DB::table('mtr_tbl_bpd_project')->pluck('id')->toArray() ?: [1];
            $bpdKelompok = DB::table('mtr_tbl_bpd_kelompok')->pluck('id')->toArray() ?: [1];
            $startId = (int) DB::table('trx_tbl_bpd_master')->max('id');

            $bpdMaster = [];
            $bpdInfo = [];
            $bpdBiaya = [];
            $bpdLog = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $pengaju = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 2) % $totalKaryawan];
                $startDate = Carbon::now()->subDays(rand(1, 90));
                $endDate = (clone $startDate)->addDays(rand(2, 5));
                $statusId = $bpdStatusIds[array_rand($bpdStatusIds)];
                $projId = $bpdProjects[array_rand($bpdProjects)];
                $kelId = $bpdKelompok[array_rand($bpdKelompok)];

                $bpdMaster[] = [
                    'id' => $txId,
                    'is_ca' => 1,
                    'periode' => $startDate->year,
                    'luar_dalam' => 'Dalam Negeri',
                    'is_project' => 1,
                    'project_id' => $projId,
                    'kelompok_id' => $kelId,
                    'is_leader' => 1,
                    'pengaju' => $pengaju->nik,
                    'pengaju_property' => $formatProp($pengaju),
                    'atasan' => $atasan->nik,
                    'atasan_property' => $formatProp($atasan),
                    'admin' => $atasan->nik,
                    'admin_property' => $formatProp($atasan),
                    'koordinator' => $atasan->nik,
                    'koordinator_property' => $formatProp($atasan),
                    'layer1' => $atasan->nik,
                    'layer1_property' => $formatProp($atasan),
                    'layer2' => $atasan->nik,
                    'layer2_property' => $formatProp($atasan),
                    'hco' => $atasan->nik,
                    'hco_property' => $formatProp($atasan),
                    'uraian' => 'Kunjungan kerja teknis dan evaluasi instalasi jaringan proyek #' . $txId,
                    'lampiran' => 'surat_tugas_' . $txId . '.pdf',
                    'status_id' => $statusId,
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $bpdInfo[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'jenis' => 'Project',
                    'luar_dalam' => 'Dalam Negeri',
                    'tujuan1' => 'Surabaya',
                    'tujuan2' => 'Malang',
                    'tanggal_mulai' => $startDate->format('Y-m-d'),
                    'tanggal_akhir' => $endDate->format('Y-m-d'),
                    'is_approve' => 1,
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $bpdBiaya[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'jenis' => 'Transport',
                    'id_info' => $txId,
                    'id_coa' => 1,
                    'kode_coa' => '510101',
                    'biaya' => 'Tiket Pesawat & Transportasi Lokal',
                    'satuan' => 'Paket',
                    'jumlah' => 1,
                    'nominal' => 2500000,
                    'lampiran' => 'tiket_' . $txId . '.pdf',
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $bpdLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Pengajuan BPD disetujui atasan',
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];
            }
            $safeInsert('trx_tbl_bpd_master', $bpdMaster, 30);
            $safeInsert('trx_tbl_bpd_detail_info', $bpdInfo, 50);
            $safeInsert('trx_tbl_bpd_detail_biaya', $bpdBiaya, 50);
            $safeInsert('trx_tbl_bpd_log', $bpdLog, 50);
        }

        // -------------------------------------------------------------
        // 7. PENILAIAN KINERJA (trx_tbl_penilaian, log, skor, auto, history)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_penilaian')->count() < $totalTx) {
            $this->command->info('Membuat data Penilaian Kinerja...');
            $penilaianTipeIds = DB::table('mtr_tbl_penilaian_tipe')->pluck('id')->toArray() ?: [1, 2];
            $penilaianStatusIds = DB::table('mtr_tbl_penilaian_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $penNilai3 = DB::table('mtr_tbl_penilaian_3')->pluck('id')->toArray() ?: [1, 2];
            $startId = (int) DB::table('trx_tbl_penilaian')->max('id');

            $penMaster = [];
            $penLog = [];
            $penSkor = [];
            $penAuto = [];
            $penHist = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $dinilai = $karyawanList[($i - 1) % $totalKaryawan];
                $penilai1 = $karyawanList[($i * 2) % $totalKaryawan];
                $penilai2 = $karyawanList[($i * 3) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));
                $tipeId = $penilaianTipeIds[array_rand($penilaianTipeIds)];
                $statusId = $penilaianStatusIds[array_rand($penilaianStatusIds)];
                $idNilai3 = $penNilai3[array_rand($penNilai3)];

                $penMaster[] = [
                    'id' => $txId,
                    'nik_karyawan' => $dinilai->nik,
                    'nik_penilai_1' => $penilai1->nik,
                    'nik_penilai_2' => $penilai2->nik,
                    'karyawan_property' => $formatProp($dinilai),
                    'penilai1_property' => $formatProp($penilai1),
                    'penilai2_property' => $formatProp($penilai2),
                    'id_tipe' => $tipeId,
                    'id_status' => $statusId,
                    'is_perpanjang' => 0,
                    'bulan' => $d->month,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $penLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $penilai1->nik,
                    'keterangan' => 'Penilaian kinerja telah diinput dan disetujui',
                    'is_perpanjang' => 0,
                    'bulan' => $d->month,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $penHist[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => $statusId,
                    'nik_pic' => $penilai1->nik,
                    'keterangan' => 'History revisi evaluasi semesteran',
                    'is_perpanjang' => 0,
                    'bulan' => $d->month,
                    'revised_by' => $penilai1->nik,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $penSkor[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_nilai3' => $idNilai3,
                    'penilaian_1' => 90,
                    'penilaian_2' => 92,
                    'penilaian_2_ket' => 'Kinerja melampaui target',
                    'penilaian_3' => 88,
                    'is_spv' => 1,
                    'skor_karyawan' => 90,
                    'skor_penilai_1' => 92,
                    'skor_penilai_2' => 88,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $penAuto[] = [
                    'id' => $txId,
                    'nik_karyawan' => $dinilai->nik,
                    'nik_penilai_1' => $penilai1->nik,
                    'nik_penilai_2' => $penilai2->nik,
                    'id_tipe' => $tipeId,
                    'id_status' => $statusId,
                    'id_trx' => $txId,
                    'is_email' => 1,
                    'start_date' => $d->format('Y-m-d'),
                    'end_date' => $d->format('Y-m-d'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }
            $safeInsert('trx_tbl_penilaian', $penMaster, 40);
            $safeInsert('trx_tbl_penilaian_log', $penLog, 50);
            $safeInsert('trx_tbl_penilaian_log_history', $penHist, 50);
            $safeInsert('trx_tbl_penilaian_skor', $penSkor, 50);
            $safeInsert('trx_tbl_penilaian_auto', $penAuto, 50);
        }

        // -------------------------------------------------------------
        // 8. KEBUTUHAN KARYAWAN & RECRUITMENT
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_kebutuhan_master')->count() < 500) {
            $this->command->info('Membuat data Kebutuhan Karyawan & Recruitment...');
            $recStatusIds = DB::table('mtr_tbl_recruitment_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $kebStatusIds = DB::table('mtr_tbl_kebutuhan_status')->pluck('id')->toArray() ?: [1, 2, 4];
            $startId = (int) DB::table('trx_tbl_kebutuhan_master')->max('id');

            $kebMaster = [];
            $kebDetail = [];
            $kebLog = [];
            $kebEdit = [];
            $recMaster = [];
            $recIdentitas = [];
            $recLog = [];

            for ($i = 1; $i <= 500; $i++) {
                $txId = $startId + $i;
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 2) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));

                $kebMaster[] = [
                    'id' => $txId,
                    'pengaju' => $k->nik,
                    'layer1' => $atasan->nik,
                    'layer2' => $atasan->nik,
                    'layer3' => null,
                    'layer4' => null,
                    'layer5' => null,
                    'pengaju_property' => $formatProp($k),
                    'layer1_property' => $formatProp($atasan),
                    'layer2_property' => $formatProp($atasan),
                    'id_pekerjaan' => 1,
                    'id_level' => 1,
                    'id_status_kerja' => 1,
                    'jumlah' => 1,
                    'id_kelamin' => 1,
                    'id_area' => ($i % 34) + 1,
                    'id_kota' => 1,
                    'id_jenis_permintaan' => 1,
                    'id_rencana' => 1,
                    'id_jenjang_pendidikan' => 1,
                    'umur_minimum' => 22,
                    'umur_maksimum' => 35,
                    'id_pengalaman' => 1,
                    'tanggal' => $d->format('Y-m-d'),
                    'status_pengajuan' => 1,
                    'findim_project' => 1,
                    'findim_divisi' => 1,
                    'findim_departemen' => 1,
                    'findim_subdepartemen' => 1,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $kebDetail[] = [
                    'id' => $txId,
                    'id_kebutuhan' => $txId,
                    'nama_karyawan' => 'Calon Karyawan ' . $txId,
                    'tanggal_interview' => $d->format('Y-m-d'),
                    'file_interview' => 'interview_' . $txId . '.pdf',
                    'tanggal_psikotes' => $d->format('Y-m-d'),
                    'file_psikotes' => 'psikotes_' . $txId . '.pdf',
                    'tanggal_negosiasi' => $d->format('Y-m-d'),
                    'alamat' => 'Jakarta Barat',
                    'tanggal_lahir' => '1998-05-15',
                    'id_pendidikan' => 1,
                    'take_home_pay' => 8500000,
                    'tanggal_bergabung' => $d->copy()->addDays(14)->format('Y-m-d'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $kebLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => 1,
                    'nik_pic' => $k->nik,
                    'keterangan' => 'Permintaan penambahan tenaga kerja disubmit',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $kebEdit[] = [
                    'id' => $txId,
                    'id_kebutuhan' => $txId,
                    'kolom' => 'kualifikasi',
                    'sebelum' => 'S1 Teknik',
                    'sesudah' => 'S1 Ilmu Komputer / Teknik Informatika',
                    'keterangan' => 'Penyesuaian requirement teknis',
                    'pic' => $k->nik,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $recStatusId = $recStatusIds[array_rand($recStatusIds)];
                $recMaster[] = [
                    'id' => $txId,
                    'id_kebutuhan' => $txId,
                    'id_status' => $recStatusId,
                    'qualification' => 'Lulusan S1 Informatika, pengalaman minimal 2 tahun di bidang Laravel/PHP.',
                    'job_description' => 'Mengembangkan modul backend portal MATTA dan maintenance database MySQL.',
                    'keterangan' => 'Posisi Programmer Backend Fulltime',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $recIdentitas[] = [
                    'id' => $txId,
                    'id_kebutuhan' => $txId,
                    'id_recruitment' => $txId,
                    'nomor' => 'REC-2026-' . sprintf('%04d', $txId),
                    'nama' => 'Pelamar ' . $txId,
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1997-08-20',
                    'kelamin' => ($i % 2 === 0) ? 'L' : 'P',
                    'ktp' => '31710' . rand(1000000000, 9999999999),
                    'alamat_ktp' => 'Jl. Kebon Jeruk No. ' . $i . ', Jakarta Barat',
                    'email' => 'pelamar' . $txId . '@example.com',
                    'telepon' => '0812' . rand(10000000, 99999999),
                    'id_status' => $recStatusId,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $recLog[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'id_status' => $recStatusId,
                    'pic' => $k->nik,
                    'pic_property' => $formatProp($k),
                    'aktifitas' => 'Panggilan interview tahap 1',
                    'catatan' => 'Kandidat hadir sesuai jadwal dan memenuhi kualifikasi dasar.',
                    'lampiran' => 'cv_' . $txId . '.pdf',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }

            $safeInsert('trx_tbl_kebutuhan_master', $kebMaster, 40);
            $safeInsert('trx_tbl_kebutuhan_detail', $kebDetail, 50);
            $safeInsert('trx_tbl_kebutuhan_log', $kebLog, 50);
            $safeInsert('trx_tbl_kebutuhan_master_edit', $kebEdit, 50);
            $safeInsert('trx_tbl_recruitment', $recMaster, 50);
            $safeInsert('trx_tbl_recruitment_identitas', $recIdentitas, 50);
            $safeInsert('trx_tbl_recruitment_log', $recLog, 50);

            // Detail recruitment tambahan
            $recAnak = [];
            $recFormal = [];
            $recInterview = [];
            $recNegosiasi = [];
            $recPsikotes = [];
            $recResume = [];
            $recTiu5 = [];

            for ($i = 1; $i <= 500; $i++) {
                $txId = $startId + $i;
                $recAnak[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'nama' => 'Anak Pelamar ' . $txId,
                    'usia' => 5,
                    'kelamin' => 'L',
                    'pendidikan' => 'TK',
                    'pekerjaan' => 'Pelajar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $recFormal[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'jenjang' => 'S1',
                    'nama' => 'Universitas Indonesia',
                    'kota' => 'Depok',
                    'jurusan' => 'Teknik Informatika',
                    'tahun_masuk' => 2015,
                    'tahun_lulus' => 2019,
                    'nilai' => '3.65',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $recInterview[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'id_status' => 1,
                    'jenis' => 'User Interview',
                    'metode' => 'Online Google Meet',
                    'interviewer' => $karyawanList[($i - 1) % $totalKaryawan]->nik,
                    'file_interview' => 'interview_score_' . $txId . '.pdf',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $recNegosiasi[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'id_status' => 1,
                    'posisi' => 'Staff IT Programmer',
                    'area_kerja' => 'Jakarta HQ',
                    'divisi' => 'IT Application Development',
                    'atasan' => $karyawanList[($i * 2) % $totalKaryawan]->nik,
                    'thp' => 9000000,
                    'benefit_utama' => 'BPJS Kesehatan, BPJS Ketenagakerjaan, Asuransi Tambahan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $recPsikotes[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'jenis' => 1,
                    'nomor' => 1,
                    'soal1' => 'Logika angka',
                    'jawaban1' => 'A',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $recResume[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'nomor' => 1,
                    'urutan' => 1,
                    'pernyataan1' => 'Mampu bekerja dalam tim',
                    'jawaban' => 'Sangat Setuju',
                    'keterangan' => 'Memiliki komunikasi efektif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $recTiu5[] = [
                    'id' => $txId,
                    'id_identitas' => $txId,
                    'jenis' => 1,
                    'nomor' => 1,
                    'urutan' => 1,
                    'soal' => 'Sinonim kata Adaptasi',
                    'jawaban' => 'Penyesuaian',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $safeInsert('trx_tbl_recruitment_anak', $recAnak, 50);
            $safeInsert('trx_tbl_recruitment_formal', $recFormal, 50);
            $safeInsert('trx_tbl_recruitment_interview', $recInterview, 50);
            $safeInsert('trx_tbl_recruitment_negosiasi', $recNegosiasi, 50);
            $safeInsert('trx_tbl_recruitment_psychotest', $recPsikotes, 50);
            $safeInsert('trx_tbl_recruitment_resume', $recResume, 50);
            $safeInsert('trx_tbl_recruitment_tiu5', $recTiu5, 50);
        }

        // -------------------------------------------------------------
        // 9. PELATIHAN / TRAINING (trx_tbl_pelatihan, log, peserta, dokumen)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_pelatihan')->count() < 500) {
            $this->command->info('Membuat data Pelatihan...');
            $startId = (int) DB::table('trx_tbl_pelatihan')->max('id');
            $pelMaster = [];
            $pelPeserta = [];
            $pelLog = [];
            $pelDok = [];
            $pelSert = [];
            $pelPen = [];
            $pelKad = [];

            for ($i = 1; $i <= 500; $i++) {
                $txId = $startId + $i;
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 2) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));

                $pelMaster[] = [
                    'id' => $txId,
                    'pengaju' => $k->nik,
                    'atasan_pengaju' => $atasan->nik,
                    'peserta' => $k->nik,
                    'atasan_peserta' => $atasan->nik,
                    'reviewer1' => $atasan->nik,
                    'reviewer2' => $atasan->nik,
                    'pengaju_property' => $formatProp($k),
                    'atasan_pengaju_property' => $formatProp($atasan),
                    'peserta_property' => $formatProp($k),
                    'atasan_peserta_property' => $formatProp($atasan),
                    'ke' => 1,
                    'tahun' => $d->year,
                    'jenis' => 'Technical Training',
                    'judul' => 'Sertifikasi Keahlian Cloud & Cyber Security #' . $txId,
                    'penyelenggara' => 'Alita Academy & Partner',
                    'tanggal_mulai' => $d->format('Y-m-d'),
                    'tanggal_akhir' => $d->copy()->addDays(3)->format('Y-m-d'),
                    'tempat' => 'Training Center Alita Jakarta',
                    'biaya' => 3500000,
                    'benefit' => 'Peningkatan kompetensi cloud computing',
                    'anggaran' => 1,
                    'keterangan' => 'Pengajuan pelatihan profesional',
                    'status_pengajuan' => 2,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $pelPeserta[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $pelLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => 2,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Pelatihan disetujui atasan divisi',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $pelDok[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_peserta' => $txId,
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'nama_sertifikat' => 'Sertifikat Kompetensi #' . $txId,
                    'no_sertifikat' => 'CERT-' . $txId,
                    'penerbit' => 'Alita Academy',
                    'file_pelatihan' => 'handout_training_' . $txId . '.pdf',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $pelSert[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'nama' => $k->nama,
                    'nomor_sertifikat' => 'CERT-2026-' . sprintf('%04d', $txId),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $pelPen[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_peserta' => $txId,
                    'nik' => $k->nik,
                    'urutan' => 1,
                    'label' => 'Evaluasi Training',
                    'nomor' => 1,
                    'materi1' => 'Materi sesuai silabus',
                    'materi2' => 'Instruktur kompeten',
                    'tipe' => 'Pilihan',
                    'jawaban' => 'Sangat Baik',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $pelKad[] = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'nama' => $k->nama,
                    'judul' => 'Sertifikasi Keahlian Cloud #' . $txId,
                    'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }

            $safeInsert('trx_tbl_pelatihan', $pelMaster, 25);
            $safeInsert('trx_tbl_pelatihan_peserta', $pelPeserta, 50);
            $safeInsert('trx_tbl_pelatihan_log', $pelLog, 50);
            $safeInsert('trx_tbl_pelatihan_dokumen', $pelDok, 50);
            $safeInsert('trx_tbl_pelatihan_sertifikat', $pelSert, 50);
            $safeInsert('trx_tbl_pelatihan_penilaian', $pelPen, 50);
            $safeInsert('trx_tbl_pelatihan_sertifikat_kadaluarsa', $pelKad, 50);
        }

        // -------------------------------------------------------------
        // 10. PAYROLL (trx_tbl_payroll, addsub, log, email, bpjs, findim)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_payroll')->count() < $totalTx) {
            $this->command->info('Membuat data Payroll...');
            $startId = (int) DB::table('trx_tbl_payroll')->max('id');
            $payMaster = [];
            $payAddsub = [];
            $payBpjstk = [];
            $payBpjskes = [];
            $payLog = [];
            $payLogCopy = [];
            $payEmail = [];
            $payFindim = [];
            $payPenyesuaian = [];

            for ($i = 1; $i <= $totalTx; $i++) {
                $txId = $startId + $i;
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 60));
                $gapok = rand(60, 200) * 100000;

                $payRow = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'gapok' => $gapok,
                    'status' => 'Paid',
                    'created_by' => 'PAYROLL_SYSTEM',
                    'updated_by' => 'PAYROLL_SYSTEM',
                    'perusahaan' => $perusahaanMap[$k->kode_perusahaan] ?? 'PT ALITA PRAYA MITRA',
                    'divisi' => $divisiMap[$k->kode_divisi] ?? 'Direktorat Operasional',
                    'departemen' => $deptMap[$k->kode_departemen] ?? 'Divisi IT',
                    'subdepartemen' => $subdeptMap[$k->kode_sub_departemen] ?? 'Aplikasi',
                    'status_karyawan' => 'Tetap',
                    'status_aktif' => 'Aktif',
                    'jabatan' => $jabatanMap[$k->kode_jabatan] ?? 'Staff IT',
                    'level' => $levelMap[$k->kode_level] ?? 'Staff',
                    'paid_at' => $d->format('Y-m-d'),
                    'pajak' => $gapok * 0.05,
                    'overtime' => 500000,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $payMaster[] = $payRow;
                $payLog[] = $payRow;
                $payLogCopy[] = $payRow;

                $payAddsub[] = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'angka' => 500000,
                    'id_addsub' => 1,
                    'tipe' => 'Tunjangan Transportasi',
                    'status' => 'Approved',
                    'created_by' => 'PAYROLL_ADMIN',
                    'updated_by' => 'PAYROLL_ADMIN',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $payBpjstk[] = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'id_bpjstk' => 1,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $payBpjskes[] = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'pasangan' => 1,
                    'anak' => 2,
                    'tambahan' => 0,
                    'id_bpjskes' => 1,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $payEmail[] = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'email' => $k->email ?: ($k->nik . '@alita.id'),
                    'id_addsub' => '1',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $payFindim[] = [
                    'id' => $txId,
                    'nik' => $k->nik,
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'project_id' => 'PRJ-' . $k->findim_project,
                    'project_desc' => 'Alita MATTA Maintenance'
                ];

                $payPenyesuaian[] = [
                    'id' => $txId,
                    'no_surat' => 'SK-ADJ-' . sprintf('%04d', $txId),
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'pic' => $karyawanList[($i * 2) % $totalKaryawan]->nik,
                    'pic_property' => $formatProp($karyawanList[($i * 2) % $totalKaryawan]),
                    'id_status' => 2,
                    'bulan' => $d->month,
                    'tahun' => $d->year,
                    'catatan' => 'Penyesuaian upah tahunan terverifikasi HRD',
                    'created_at' => $d,
                    'updated_at' => $d,
                    'qrcode' => 'QR-' . $txId
                ];
            }

            $safeInsert('trx_tbl_payroll', $payMaster, 40);
            $safeInsert('trx_tbl_payroll_addsub', $payAddsub, 50);
            $safeInsert('trx_tbl_payroll_bpjstk', $payBpjstk, 50);
            $safeInsert('trx_tbl_payroll_bpjskes', $payBpjskes, 50);
            $safeInsert('trx_tbl_payroll_log', $payLog, 40);
            $safeInsert('trx_tbl_payroll_log_copy', $payLogCopy, 40);
            $safeInsert('trx_tbl_payroll_email', $payEmail, 50);
            $safeInsert('trx_tbl_payroll_findim_project', $payFindim, 50);
            $safeInsert('trx_tbl_payroll_penyesuaian_upah_log', $payPenyesuaian, 50);
        }

        // -------------------------------------------------------------
        // 11. REKOMENDASI (trx_tbl_rekomendasi_master, detail, log)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_rekomendasi_master')->count() < 500) {
            $this->command->info('Membuat data Rekomendasi Karyawan...');
            $startId = (int) DB::table('trx_tbl_rekomendasi_master')->max('id');
            $rekMaster = [];
            $rekDetail = [];
            $rekLog = [];

            for ($i = 1; $i <= 500; $i++) {
                $txId = $startId + $i;
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 2) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));

                $rekMaster[] = [
                    'id' => $txId,
                    'nik_karyawan' => $k->nik,
                    'karyawan_property' => $formatProp($k),
                    'nik_atasan' => $atasan->nik,
                    'atasan_property' => $formatProp($atasan),
                    'terhitung_tanggal' => $d->format('Y-m-d'),
                    'dasar_pengajuan' => 'Prestasi kerja istimewa dan kontribusi pada proyek strategis',
                    'catatan_pengajuan' => 'Diusulkan penyesuaian level dan gaji pokok',
                    'nik_reviewer1' => $atasan->nik,
                    'reviewer1_property' => $formatProp($atasan),
                    'status_rekomendasi' => 2,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $rekDetail[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_pengajuan' => 1,
                    'jenis_pengajuan' => 'Promosi Jabatan',
                    'sebelum' => 'Junior Staff',
                    'setelah' => 'Senior Staff',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $rekLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => 2,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Rekomendasi promosi disetujui reviewer',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }

            $safeInsert('trx_tbl_rekomendasi_master', $rekMaster, 50);
            $safeInsert('trx_tbl_rekomendasi_detail', $rekDetail, 50);
            $safeInsert('trx_tbl_rekomendasi_log', $rekLog, 50);
        }

        // -------------------------------------------------------------
        // 12. NONAKTIF (trx_tbl_nonaktif_master, detail, log, auto)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_nonaktif_master')->count() < 300) {
            $this->command->info('Membuat data Nonaktif Karyawan...');
            $startId = (int) DB::table('trx_tbl_nonaktif_master')->max('id');
            $nonMaster = [];
            $nonDetail = [];
            $nonLog = [];
            $nonAuto = [];

            for ($i = 1; $i <= 300; $i++) {
                $txId = $startId + $i;
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $atasan = $karyawanList[($i * 2) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 90));

                $nonMaster[] = [
                    'id' => $txId,
                    'pengaju' => $k->nik,
                    'pengaju_property' => $formatProp($k),
                    'atasan' => $atasan->nik,
                    'atasan_property' => $formatProp($atasan),
                    'alasan' => 1,
                    'tanggal' => $d->format('Y-m-d'),
                    'keterangan' => 'Pengunduran diri atas inisiatif sendiri / habis kontrak',
                    'status' => 2,
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $nonDetail[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    '_into_' => 'IT Asset & Facility',
                    'kepada' => $atasan->nik,
                    'kepada_property' => $formatProp($atasan),
                    'obyek' => 1,
                    'keterangan' => 'Pengembalian laptop inventaris kantor dan ID Card',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $nonLog[] = [
                    'id' => $txId,
                    'id_trx' => $txId,
                    'id_status' => 2,
                    'nik_pic' => $atasan->nik,
                    'keterangan' => 'Proses pengembalian aset selesai diverifikasi',
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $nonAuto[] = [
                    'id' => $txId,
                    'nik_karyawan' => $k->nik,
                    'nik_atasan' => $atasan->nik,
                    'id_status' => 2,
                    'id_trx' => $txId,
                    'is_email' => 1,
                    'start_date' => $d->format('Y-m-d'),
                    'end_date' => $d->format('Y-m-d'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }

            $safeInsert('trx_tbl_nonaktif_master', $nonMaster, 50);
            $safeInsert('trx_tbl_nonaktif_detail', $nonDetail, 50);
            $safeInsert('trx_tbl_nonaktif_log', $nonLog, 50);
            $safeInsert('trx_tbl_nonaktif_auto', $nonAuto, 50);
        }

        // -------------------------------------------------------------
        // 13. REMINDER SP (trx_tbl_remindersp & send)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_remindersp')->count() < 300) {
            $this->command->info('Membuat data Reminder SP...');
            $startId = (int) DB::table('trx_tbl_remindersp')->max('id');
            $spBatch = [];
            $spSendBatch = [];

            for ($i = 1; $i <= 300; $i++) {
                $txId = $startId + $i;
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 60));

                $spBatch[] = [
                    'id' => $txId,
                    'date_from' => $d->copy()->startOfMonth()->format('Y-m-d'),
                    'date_to' => $d->copy()->endOfMonth()->format('Y-m-d'),
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'arr_base' => 22,
                    'arr_clock' => 18,
                    'persentase' => 81.82,
                    'created_at' => $d,
                    'updated_at' => $d,
                    'terlambat' => '3 kali terlambat datang bulan ini',
                ];

                $spSendBatch[] = [
                    'id' => $txId,
                    'number' => $txId,
                    'type' => 'Peringatan Kehadiran',
                    'date_from' => $d->copy()->startOfMonth()->format('Y-m-d'),
                    'date_to' => $d->copy()->endOfMonth()->format('Y-m-d'),
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'arr_base' => 22,
                    'arr_clock' => 18,
                    'persentase' => 81.82,
                    'body_email' => 'Yth. Karyawan, mohon memperhatikan kedisiplinan jam kerja MATTA.',
                    'created_at' => $d,
                    'updated_at' => $d,
                    'terlambat' => '3 kali',
                ];
            }

            $safeInsert('trx_tbl_remindersp', $spBatch, 50);
            $safeInsert('trx_tbl_remindersp_send', $spSendBatch, 50);
        }

        // -------------------------------------------------------------
        // 14. UTILITIES (Feeds, Todolist, Birthday, Survey, Holiday, Debug, PDP, Kesehatan, Download)
        // -------------------------------------------------------------
        if (DB::table('trx_tbl_feeds')->count() < 50) {
            $this->command->info('Membuat data Feeds & Pengumuman...');
            $feedTitles = [
                'Pengumuman Libur Hari Raya & Kebijakan Cuti Bersama',
                'Sosialisasi Penggunaan Portal MATTA Versi Terbaru',
                'Ketentuan Jam Kerja dan Absensi Bulan Ini',
                'Pembaruan Plafon Klaim Kesehatan Karyawan',
                'Pelatihan Teknis Internal dan Program Sertifikasi Pegawai',
                'Jadwal Medical Check Up (MCU) Tahunan',
                'Kegiatan Townhall Meeting dan Apresiasi Kinerja Karyawan',
                'Tips Menjaga Kesehatan & Keselamatan Kerja (K3) di Proyek',
                'Kebijakan Perjalanan Dinas (BPD) & Prosedur Cash Advance',
                'Peringatan Hari Kemerdekaan & Lomba Kebersamaan Karyawan'
            ];
            $adminNik = $karyawanList->first()->nik;
            $feedBatch = [];
            for ($i = 1; $i <= 50; $i++) {
                $t = $feedTitles[($i - 1) % count($feedTitles)] . " (Edisi {$i})";
                $feedBatch[] = [
                    'title' => $t,
                    'content' => 'Pengumuman resmi Alita mengenai ' . strtolower($t) . ' untuk seluruh jajaran karyawan Alita. Mohon dipedomani dengan seksama.',
                    'is_active' => 1,
                    'is_notif' => 1,
                    'created_by' => $adminNik,
                    'updated_by' => $adminNik,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 60)),
                ];
            }
            $safeInsert('trx_tbl_feeds', $feedBatch, 50);
        }

        if (DB::table('trx_tbl_holiday')->count() < 10) {
            $this->command->info('Membuat data Hari Libur Nasional...');
            $holidays = [
                ['tanggal' => '2026-01-01', 'libur' => 'Tahun Baru 2026 Masehi', 'is_cuti' => 0],
                ['tanggal' => '2026-02-17', 'libur' => 'Tahun Baru Imlek 2577 Kongzili', 'is_cuti' => 0],
                ['tanggal' => '2026-03-20', 'libur' => 'Hari Raya Idul Fitri 1447 Hijriah', 'is_cuti' => 0],
                ['tanggal' => '2026-03-21', 'libur' => 'Hari Raya Idul Fitri 1447 Hijriah (Hari Kedua)', 'is_cuti' => 0],
                ['tanggal' => '2026-03-23', 'libur' => 'Cuti Bersama Idul Fitri', 'is_cuti' => 1],
                ['tanggal' => '2026-05-01', 'libur' => 'Hari Buruh Internasional', 'is_cuti' => 0],
                ['tanggal' => '2026-05-14', 'libur' => 'Kenaikan Yesus Kristus', 'is_cuti' => 0],
                ['tanggal' => '2026-06-01', 'libur' => 'Hari Lahir Pancasila', 'is_cuti' => 0],
                ['tanggal' => '2026-08-17', 'libur' => 'Hari Kemerdekaan Republik Indonesia ke-81', 'is_cuti' => 0],
                ['tanggal' => '2026-12-25', 'libur' => 'Hari Raya Natal', 'is_cuti' => 0],
            ];
            $hBatch = [];
            foreach ($holidays as $h) {
                $hBatch[] = array_merge($h, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $safeInsert('trx_tbl_holiday', $hBatch, 50);
        }

        if (DB::table('trx_tbl_todolist')->count() < $totalTx) {
            $this->command->info('Membuat data Todolist / Tugas...');
            $todoBatch = [];
            $todoLogBatch = [];
            for ($i = 1; $i <= $totalTx; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $pic = $karyawanList[($i * 3) % $totalKaryawan];
                $d = Carbon::now()->subDays(rand(1, 30));

                $todoBatch[] = [
                    'id_trx' => $i,
                    'id_status' => rand(1, 4),
                    'nik' => $k->nik,
                    'pic' => $pic->nik,
                    'title' => 'Persetujuan Pengajuan ' . (['Cuti', 'Lembur', 'Izin', 'Reimburse', 'BPD'][rand(0, 4)]) . ' #' . $i,
                    'nama' => $k->nama,
                    'jabatan' => $jabatanMap[$k->kode_jabatan] ?? 'Staff IT',
                    'unit_kerja' => $deptMap[$k->kode_departemen] ?? 'Operasional IT',
                    'link' => '/approval/' . $i,
                    'email' => $k->email ?: ($k->nik . '@alita.id'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];

                $todoLogBatch[] = [
                    'id_trx' => $i,
                    'id_status' => rand(1, 4),
                    'nik' => $k->nik,
                    'pic' => $pic->nik,
                    'title' => 'Log Aktivitas Todolist #' . $i,
                    'nama' => $k->nama,
                    'jabatan' => $jabatanMap[$k->kode_jabatan] ?? 'Staff IT',
                    'unit_kerja' => $deptMap[$k->kode_departemen] ?? 'Operasional IT',
                    'link' => '/approval/' . $i,
                    'email' => $k->email ?: ($k->nik . '@alita.id'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ];
            }
            $safeInsert('trx_tbl_todolist', $todoBatch, 50);
            $safeInsert('trx_tbl_todolist_log', $todoLogBatch, 50);
        }

        if (DB::table('trx_tbl_birthday')->count() < $totalTx) {
            $this->command->info('Membuat data Ulang Tahun Karyawan...');
            $bdayBatch = [];
            $bdayLogBatch = [];
            for ($i = 1; $i <= $totalTx; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $bdayDate = Carbon::create(1990 + rand(0, 12), rand(1, 12), rand(1, 28));

                $emailData = json_encode([
                    'subject' => 'Happy Birthday',
                    'body' => '<div class="container"><p>Happy Birthday to <b>' . ($k->nama ?? $k->nik) . '</b></p></div>',
                    'to' => $k->email ?: ($k->nik . '@alita.id'),
                    'cc' => 'hc@alita.id',
                ]);

                $bdayBatch[] = [
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'email' => $emailData,
                    'birthday' => $bdayDate->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $bdayLogBatch[] = [
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'email' => $emailData,
                    'birthday' => $bdayDate->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_birthday', $bdayBatch, 50);
            $safeInsert('trx_tbl_birthday_log', $bdayLogBatch, 50);
        }

        if (DB::table('trx_tbl_survey_pora')->count() < $totalTx) {
            $this->command->info('Membuat data Survey PORA...');
            $surveyBatch = [];
            for ($i = 1; $i <= $totalTx; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $surveyBatch[] = [
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'opsi1' => 'Sangat Puas',
                    'opsi2' => 'Fasilitas Sangat Mendukung',
                    'opsi3' => 'Tingkatkan Pelatihan Teknis',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_survey_pora', $surveyBatch, 50);
        }

        if (DB::table('trx_tbl_pdp')->count() < $totalTx) {
            $pdpBatch = [];
            for ($i = 1; $i <= $totalTx; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $pdpBatch[] = [
                    'nik' => $k->nik,
                    'nik_property' => $formatProp($k),
                    'is_setuju' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_pdp', $pdpBatch, 50);
        }

        if (DB::table('trx_tbl_absensi_kesehatan')->count() < $totalTx) {
            $kesBatch = [];
            for ($i = 1; $i <= $totalTx; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $kesBatch[] = [
                    'karyawan' => $k->nik,
                    'karyawan_property' => substr($formatProp($k), 0, 490),
                    'tanggal_input' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                    'ring_ke' => 'Ring 1',
                    'ring_isian' => 'Sehat dan Bugar',
                    'siapa' => 'Diri Sendiri',
                    'sakit' => 'Tidak Ada',
                    'tanggal_sakit' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_absensi_kesehatan', $kesBatch, 50);
        }

        if (DB::table('trx_tbl_email_log')->count() < 500) {
            $emailBatch = [];
            for ($i = 1; $i <= 500; $i++) {
                $k = $karyawanList[($i - 1) % $totalKaryawan];
                $emailBatch[] = [
                    'title' => 'Notifikasi Pengajuan Online MATTA #' . $i,
                    'filename' => 'dokumen_' . $i . '.pdf',
                    'to' => $k->email ?: ($k->nik . '@alita.id'),
                    'cc' => 'hrd@alita.id',
                    'data' => json_encode(['status' => 'sent', 'message' => 'Notifikasi berhasil terkirim']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_email_log', $emailBatch, 50);
        }

        if (DB::table('trx_tbl_debug')->count() < 200) {
            $dbgBatch = [];
            for ($i = 1; $i <= 200; $i++) {
                $dbgBatch[] = [
                    'nomor' => 'DBG-' . sprintf('%04d', $i),
                    'modul' => 'Attendance/Payroll/Cuti',
                    'url' => '/api/v1/sync/' . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_debug', $dbgBatch, 50);
        }

        if (DB::table('trx_tbl_attendance_download')->count() < 50) {
            $dlBatch = [];
            for ($i = 1; $i <= 50; $i++) {
                $dlBatch[] = [
                    'tanggal_mulai' => Carbon::now()->subDays(60)->format('Y-m-d'),
                    'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
                    'link' => '/downloads/rekap_kehadiran_' . $i . '.xlsx',
                    'is_exe' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $safeInsert('trx_tbl_attendance_download', $dlBatch, 50);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('SELESAI! Seluruh tabel modul transaksi utama telah terisi dummy data secara lengkap.');
    }
}
