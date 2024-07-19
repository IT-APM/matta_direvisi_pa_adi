<p>Dear <?php echo e($data['atasan']); ?>,</p>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Pengajuan Lembur yang harus anda review</strong></p>
<p></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="20%">Nama Karyawan</td>
    <td width="80%">: <?php echo e($data['nama']); ?></td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>: <?php echo e($data['jabatan']); ?></td>
  </tr>
  <tr>
    <td>Direktorat / Divisi / Departemen</td>
    <td>: <?php echo e($data['unit_kerja']); ?></td>
  </tr>
  <tr>
    <td>Pekerjaan</td>
    <td>: <?php echo e($data['overtime']['ot_title']); ?></td>
  </tr>
  <tr>
    <td>Deskripsi</td>
    <td>: <?php echo e($data['overtime']['ot_desc']); ?></td>
  </tr>
  <tr>
    <td>Tipe</td>
    <td>: <?php echo e($data['overtime']['ot_type']); ?></td>
  </tr>
  <tr>
    <td>Waktu mulai</td>
    <td>: <?php echo e($data['overtime']['ot_start_at']); ?></td>
  </tr>
  <tr>
    <td>Waktu akhir</td>
    <td>: <?php echo e($data['overtime']['ot_end_at']); ?></td>
  </tr>
</table>
</p>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Pengajuan lembur</a></strong></h1>
<p>Syarat dan Ketentuan OVERTIME (OT) :<br/>
1. OT yang Anda buat dan ajukan TIDAK BERIRISAN dengan OT Anda yang lain.<br/>
2. OT TIDAK BISA DILAKUKAN pada jam kerja normal / regular Anda.<br/>
3. Jam mulai OT di HARI KERJA adalah 1 (satu) jam setelah berakhirnya JAM KERJA REGULER.<br/>
Contoh : Jam Kerja Reguler pukul 09.00 – 18.00 maka OT dimulai pukul pukul 19.00<br/>
4. Pengajuan OT bisa dilakukan dalam hari atau bulan yang berbeda.<br/>
Contoh : Pengajuan OT untuk besok atau pekan depan dapat dibuat saat ini<br/>
5. Klaim OT yang dihitung adalah jika memenuhi SEMUA SYARAT berikut :<br/>
A. OT yang telan mendapatkan PERSETUJUAN Atasan terkait.<br/>
B. Durasi OT yang dihitung adalah WAKTU CLOCK IN dan CLOCK OUT pada periode pengajuan OT. Selain durasi diluar periode OT, TIDAK DIHITUNG SEBAGAI REALISASI OT.<br/>
C. Kegiatan CLOCK IN dan CLOCK OUT WAJIB dilakukan seluruhnya oleh Pengaju OT. Tanpa CLOCK IN dan CLOCK OUT, perhitungan OT tidak muncul di sistem dan dianggap tidak mempunyai realisasi OT.</p>
<p>Terima kasih,</p>
<p><strong>HC</strong></p><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/lembur0.blade.php ENDPATH**/ ?>