<!DOCTYPE html>
<html lang="en">

<head>
    <title>Surat Peringatan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<?php $nik_property = json_decode($data['trx']->nik_property, true); ?>

<body>
    <div style="text-align:center;">
        <img src="https://matta-reborn.alita.id/matta/assets/images/matta/sudah_absen_hari_ini.png" alt="Sudahkah anda absen hari ini" width="720" height="479">
    </div>
    <div style="text-align:center;background-color:red;color:white;">
        <p style="padding:5px;">Surat Peringatan <?php echo e($data['teguran_ke']); ?></p>
    </div>
    <div>
        <div>
            <div>
                <p>Dear Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?></p>
                <p>Terima kasih telah menggunakan Aplikasi Mobile / Web MATTA untuk kegiatan Absensi Kehadiran. Kami informasikan bahwa Absensi Kehadiran wajib dilakukan sekurang-kurangnya 80%.</p>
                <p>Bersama ini Kami sampaikan bahwa absensi kehadiran Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?> pada tanggal <?php echo e(\Carbon\Carbon::parse($data['trx']->date_from)->format('d-m-Y')); ?> sd <?php echo e(\Carbon\Carbon::parse($data['trx']->date_to)->format('d-m-Y')); ?> kurang dari 80% dari yang seharusnya terpenuhi (hadir = <?php echo e($data['trx']->arr_clock); ?> dari jumlah hari kerja = <?php echo e($data['trx']->arr_base); ?>).</p>
                <p>Persentase kehadiran anda adalah <?php echo e(round($data['trx']->persentase)); ?> %.</p>
                <div style="text-align:center;background-color:red;color:white;">
                    <?php if($data['trx']->arr_clock == 0): ?>
                    <p style="padding:5px;">Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?> tidak melakukan Absensi Kehadiran sama sekali</p>
                    <?php else: ?>
                    <p style="padding:5px;">Mohon untuk dapat lebih ditingkatkan lagi Absensi Kehadirannya</p>
                <?php endif; ?>
                </div>
                <?php if($data['teguran_ke'] == 'Pertama'): ?>
                <p>Sehubungan dengan Surat Teguran Pertama sampai dengan Surat Teguran Kelima telah kami kirimkan melalui email seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan <?php echo e($data['teguran_ke']); ?> (akan dikirimkan dalam email terpisah).</p>
                <p>Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Kedua. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                <?php elseif($data['teguran_ke'] == 'Kedua'): ?>
                <p>Sehubungan dengan Surat Peringatan Pertama telah kami kirimkan melalui email seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan <?php echo e($data['teguran_ke']); ?> (akan dikirimkan dalam email terpisah).</p>
                <p>Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Ketiga. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                <?php elseif($data['teguran_ke'] == 'Ketiga'): ?>
                <p>Sehubungan dengan Surat Peringatan Kedua telah kami kirimkan melalui email seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan <?php echo e($data['teguran_ke']); ?> (akan dikirimkan dalam email terpisah).</p>
                <p>Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Ketiga. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                <?php endif; ?>
                <p>Terima kasih atas perhatiannya.</p>
                <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                <p><?php echo e($data['datetime']); ?></p>
            </div>
        </div>
    </div>
</body>

</html>

<?php echo $data['history_email']; ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/warning1.blade.php ENDPATH**/ ?>