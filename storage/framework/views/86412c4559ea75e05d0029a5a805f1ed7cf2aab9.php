<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Pengajuan Formulir Izin anda telah disetujui.</strong></p>
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
        <td>Nama Atasan Langsung</td>
        <td>: <?php echo e($data['atasan']); ?></td>
    </tr>
</table>
</p>
<p>Klik link berikut untuk download pdf Formulir Izin:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Izin</a></strong></h1>
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/izin_setuju.blade.php ENDPATH**/ ?>