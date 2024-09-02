<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Karyawan Non Aktif yang harus anda input.</strong></p>
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
</table>
</p>
<p>Klik link berikut untuk melakukan input tersebut:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Karyawan Non Aktif</a></strong></h1>
<p>Terima kasih,</p>
<p><strong>HC</strong></p><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/nonaktif0_auto.blade.php ENDPATH**/ ?>