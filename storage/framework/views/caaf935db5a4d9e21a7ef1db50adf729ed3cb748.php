<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi <?php echo e($data['nama_to']); ?> / <?php echo e($data['nik_to']); ?>,</p>
<p><strong>Pengajuan <?php echo e($data['title']); ?> anda sudah <b style='color:green;'>SELESAI</b> disetujui para pihak.</strong></p>
<p></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td>No Formulir</td>
    <td>: <?php echo e(Crypt::decrypt($data['id'])); ?></td>
  </tr>
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
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/bpd_done.blade.php ENDPATH**/ ?>