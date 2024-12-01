<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Rekomendasi Karyawan yang harus anda proses</strong></p>
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
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/rekomendasi1.blade.php ENDPATH**/ ?>