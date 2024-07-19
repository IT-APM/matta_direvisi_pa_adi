<p>Semangat Pagi,</p>
<p><strong>Formulir Permohonan Cuti anda telah disetujui para pihak</strong></p>
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
    <td>Nama Atasan Langsung</td>
    <td>: <?php echo e($data['atasan']); ?></td>
  </tr>
</table>
</p>
<p>Klik link berikut untuk download pdf Formulir Permohonan Cuti:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Permohonan Cuti</a></strong></h1>
<p>Terima kasih,</p>
<p><strong>HC</strong></p><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/cuti_done.blade.php ENDPATH**/ ?>