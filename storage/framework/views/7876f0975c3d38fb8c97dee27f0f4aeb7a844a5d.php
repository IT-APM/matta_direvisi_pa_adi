<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
</style>
<head>
	<title>Matta Reborn</title>
</head>

<body>
	<!-- start -->
	<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center"><strong>FORM PERMOHONAN CUTI</strong></div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trxCuti->id); ?></div></td>
					</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>DIAJUKAN OLEH</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen</td>
						<td width="70%">: <?php echo e($karyawan['nama']); ?><br />: <?php echo e($karyawan['nik']); ?><br />: <?php echo e($karyawan['jabatan']); ?><br />: <?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?> </td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>TIPE CUTI</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tipe Cuti</td>
						<td width="70%">: <?php echo e($trxCuti->_tipe->tipe_cuti); ?></td>
					</tr>
					<tr>
						<td>Tahun Periode Cuti</td>
						<td>: <?php echo e($trxCuti->periode_cuti); ?></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>MASA CUTI</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Mengajukan cuti pada</td>
						<td width="70%">: <?php echo e($arrTanggal['tanggal_mulai']); ?> sampai dengan <?php echo e($arrTanggal['tanggal_akhir']); ?></td>
					</tr>
					<tr>
						<td>Jumlah hari cuti</td>
						<td>: <?php echo e($trxCuti->jumlah_hari); ?></td>
					</tr>
					<tr>
						<td>Tanggal cuti</td>
						<td><?php $__currentLoopData = $arrTanggal['arrTanggalCuti']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>: <?php echo e($value); ?><br /><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</td>
					</tr>
					<tr>
						<td>Masuk bekerja kembali</td>
						<td>: <?php echo e($arrTanggal['tanggal_masuk']); ?></td>
					</tr>
					<tr>
						<td>Alasan pengajuan cuti</td>
						<td>: <?php echo $trxCuti->alasan; ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>: <?php echo $trxCuti->alamat; ?></td>
					</tr>
					<tr>
						<td>Nomor telepon</td>
						<td>: <?php echo e($trxCuti->telepon); ?></td>
					</tr>
					<tr>
						<td>Keterangan</td>
						<td>: <?php echo e($trxCuti->keterangan); ?></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>PERSONEL PENGGANTI</strong></td>
					</tr>
				</table>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<th width="30%">Nama / NIP</th>
						<th width="45%">Jabatan / Direktorat / Divisi / Departemen</th>
						<th width="25%">Telepon / Email</th>
					</tr>
					<?php $__currentLoopData = $arrPicProperty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($value['nama']); ?><br /><?php echo e($value['nik']); ?></td>
						<td><?php echo e($value['jabatan']); ?><br /><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></td>
						<td><?php echo e($value['telepon1']); ?><br /><?php echo e($value['email']); ?></td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>RESUME</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tipe Cuti</td>
						<td width="70%">: <?php echo e($trxCuti->_tipe->tipe_cuti); ?></td>
					</tr>
					<tr>
						<td>Tahun Periode Cuti</td>
						<td>: <?php echo e($trxCuti->periode_cuti); ?></td>
					</tr>
					<tr>
						<td>Jumlah hari cuti</td>
						<td>: <?php echo e($trxCuti->jumlah_hari); ?></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>CATATAN ATASAN</strong></td>
					</tr>
				</table>
				<hr />
				<?php
				$where = ['id_trx' => $trxCuti->id];
				$catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td><?php if(!empty($catatan)): ?><?php echo e($catatan->keterangan); ?><?php endif; ?></td>
					</tr>
				</table>
				<hr />
				<?php echo $htmlCutiLog; ?>

			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/pdf.blade.php ENDPATH**/ ?>