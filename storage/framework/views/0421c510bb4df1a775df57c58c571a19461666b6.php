<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Pengajuan Pelatihan yang harus anda review</strong></p>
<p></p>
<p>Berikut datanya:</p>
<strong>Pengaju:</strong><br/>
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
<strong>Pelatihan:</strong><br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="20%">Judul</td>
    <td width="80%">: <?php echo e($data['trx']->judul); ?></td>
  </tr>
  <tr>
    <td>Penyelenggara</td>
    <td>: <?php echo e($data['trx']->penyelenggara); ?></td>
  </tr>
  <tr>
    <td>Tanggal Pelaksanaan</td>
    <td>: <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_mulai)->format('d-m-Y')); ?> sampai <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_akhir)->format('d-m-Y')); ?></td>
  </tr>
  <tr>
    <td>Tempat</td>
    <td>: <?php echo e($data['trx']->tempat); ?></td>
  </tr>
  <tr>
    <td>Peserta</td>
    <td>: 
    <?php $arrPeserta = explode(';', $data['trx']->peserta_property) ?>
      <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $_value = json_decode($value, true) ?>
        <?php echo e($_value['nama']); ?> /  <?php echo e($_value['nik']); ?><br/>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </td>
  </tr>
</table>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Pengajuan Pelatihan</a></strong></h1>
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/pelatihan0.blade.php ENDPATH**/ ?>