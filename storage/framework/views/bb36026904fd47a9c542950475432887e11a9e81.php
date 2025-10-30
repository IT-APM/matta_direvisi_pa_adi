<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Pengajuan Pelatihan yang harus anda proses</strong></p>
<p>Berikut datanya:</p>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="30%">No Formulir</td>
    <td width="2%">:</td>
    <td width="68%"><?php echo e($data['trx']->id); ?></td>
  </tr>
</table>
<p></p>
<strong>Pengaju:</strong><br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="30%">Nama Karyawan</td>
    <td width="2%">:</td>
    <td width="68%"><?php echo e($data['nama']); ?></td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>:</td>
    <td><?php echo e($data['jabatan']); ?></td>
  </tr>
  <tr>
    <td>Direktorat / Divisi / Departemen</td>
    <td>:</td>
    <td><?php echo e($data['unit_kerja']); ?></td>
  </tr>
</table>
<p></p>
<strong>Pelatihan:</strong><br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="30%">Judul</td>
    <td width="2%">:</td>
    <td width="68%"><?php echo e($data['trx']->judul); ?></td>
  </tr>
  <tr>
    <td>Penyelenggara</td>
    <td>:</td>
    <td><?php echo e($data['trx']->penyelenggara); ?></td>
  </tr>
  <?php
  $tanggal_range = [];
  if(!empty($data['trx']->tanggal_range)){
      $tanggal_range = json_decode($data['trx']->tanggal_range, true);
      $formatted_ranges = array_map(function($range) {
          [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
          return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
      }, $tanggal_range);
  }
  ?>
  <tr>
    <td>Tanggal Pelaksanaan</td>
    <td>:</td>
    
    <?php if(empty($data['trx']->tanggal_range)): ?>
    <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_mulai)->format('d-m-Y')); ?> s/d <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_akhir)->format('d-m-Y')); ?></td>
    <?php else: ?>
    <td><?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal_range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal_range); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
    <?php endif; ?>
  </tr>
  <tr>
    <td>Tempat</td>
    <td>:</td>
    <td><?php echo e($data['trx']->tempat); ?></td>
  </tr>
  <tr>
    <td>Peserta</td>
    <td>:</td>
    <td><?php $arrPeserta = explode(';', $data['trx']->peserta_property) ?>
      <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $_value = json_decode($value, true) ?>
        <?php echo e($_value['nama']); ?> /  <?php echo e($_value['nik']); ?><br/>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </td>
  </tr>
</table>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Pengajuan Pelatihan</a></strong></h1>
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/pelatihan1.blade.php ENDPATH**/ ?>