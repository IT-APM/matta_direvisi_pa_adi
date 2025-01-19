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
<?php
$pengaju_property = json_decode($trxMaster->pengaju_property, true);
$atasan_property = json_decode($trxMaster->atasan_property, true);
$koordinator_property = json_decode($trxMaster->koordinator_property, true);
?>
<body>
	<!-- start -->
	<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM BIAYA PERJALANAN DINAS</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trxMaster->id); ?></div></td>
					</tr>
				</table>
				<!--  -->
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>
						<?php if($trxMaster->luar_dalam == 'ca_dalam'): ?> REALISASI PERJALANAN DINAS DALAM NEGERI
						<?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?> REALISASI PERJALANAN DINAS LUAR NEGERI
						<?php endif; ?>
						</strong></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>PELAKSANA PERJALANAN DINAS</strong></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Nama Karyawan<br/>NIP<br/>Jabatan<br/>Level<br/>Direktorat / Divisi / Departemen<br/>Nama Atasan Langsung</td>
						<td width="70%">: <?php echo e($pengaju_property['nama']); ?><br/>: <?php echo e($pengaju_property['nik']); ?><br/>: <?php echo e($pengaju_property['jabatan']); ?><br/>: <?php echo e($pengaju_property['level']); ?><br/>: <?php echo e($pengaju_property['divisi']); ?> / <?php echo e($pengaju_property['dep']); ?> / <?php echo e($pengaju_property['sub_dep']); ?><br/>: <?php echo e($atasan_property['nama']); ?> / <?php echo e($atasan_property['nik']); ?></td>
					</tr>
				</table>
				<hr/>
				<!--  -->
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>INFORMASI PERJALANAN DINAS</strong></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tahun Pengajuan<br/>Golongan<br/>Kelompok Perjalanan Dinas<br/>Uraian Tugas Perjalanan Dinas</td>
						<td width="70%">: <?php echo e($trxMaster->periode); ?><br/>: <?php if($trxMaster->is_leader==1): ?> Leader <?php elseif($trxMaster->is_leader==2): ?> Member <?php elseif($trxMaster->is_leader==3): ?> Management <?php endif; ?><br/>: <?php if($trxMaster->is_project==1): ?>Project: <?php else: ?> Corporate: <?php endif; ?> <?php echo e($trxMaster->_kelompok->kelompok); ?><br/>: <?php echo nl2br(str_replace('<', ' ', $trxMaster->uraian)); ?></td>
					</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Jenis BPD<br/>Project ID <?php if($trxMaster->is_project == 1): ?><br/>Coordinator <?php endif; ?></td>
						<td width="70%">: <?php if($trxMaster->is_project == 1): ?> Project <?php else: ?> Corporate <?php endif; ?><br/>: <?php echo e($trxMaster->_project->project_id); ?> / <?php echo e($trxMaster->_project->name); ?> / <?php echo e($trxMaster->_project->group); ?> <?php if($trxMaster->is_project == 1): ?><br/>: <?php echo e($koordinator_property['nama']); ?> / <?php echo e($koordinator_property['nik']); ?> <?php endif; ?></td>
					</tr>
				</table>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%">#</td>
                        <td width="50%">Tujuan</td>
						<td width="12%">Tanggal dari</td>
						<td width="13%">Tanggal sampai</td>
						<td width="11%">Jumlah hari</td>
						<td width="9%">Uang saku</td>
					</tr>
					<?php $__currentLoopData = $trxDetailInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr align="center">
						<td><?php echo e(++$no_info); ?></td>
						<?php if($trxMaster->luar_dalam == 'ca_dalam'): ?>
						<td align="left"><?php echo e($data->_tujuan1_dalam->propinsi); ?> - <?php echo e($data->_tujuan2_dalam->kota); ?></td>
						<?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?>
						<td align="left"><?php echo e($data->_tujuan1_luar->negara); ?></td>
						<?php endif; ?>
						<td><?php echo e(\Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y')); ?></td>
						<td><?php echo e(\Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y')); ?></td>
						<td><?php echo e(\Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_akhir))+1); ?></td>
						<td><?php if($data->is_approve==1): ?> Ada <?php else: ?> Tidak ada <?php endif; ?></td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</table>
				<hr/>
				<!--  -->
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>BIAYA PERJALANAN DINAS</strong></td>
					</tr>
				</table>
				<hr/>
				<!--  -->
				<?php
				$total_p_idr = 0;
				$total_p_usd = 0;
				$total_l_idr = 0;
				$total_l_usd = 0;
                $total_p_idr_realisasi = 0;
                $total_p_usd_realisasi = 0;
                $total_l_idr_realisasi = 0;
                $total_l_usd_realisasi = 0;
				?>
				<?php if(count($trxDetailBiayaPersonel)>0): ?>
					<?php $__currentLoopData = $trxDetailBiayaPersonel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					if($data->satuan == 'IDR'){
						$total_p_idr += $data->nominal * $data->jumlah;
					}else if($data->satuan == 'USD'){
						$total_p_usd += $data->nominal * $data->jumlah;
					}
					?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
				<?php if(count($trxDetailBiayaPersonelRealisasi)>0): ?>
				<strong>&nbsp;Biaya Personel</strong><br/>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%">#</td>
						<td width="10%">Kode COA</td>
						<td width="40%">Keterangan</td>
						<td width="5%">Qty</td>
						<td width="15%">Nominal</td>
						<td width="10%">Satuan</td>
						<td width="15%">Total</td>
					</tr>
					<?php $__currentLoopData = $trxDetailBiayaPersonelRealisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					$kode_coa = explode('-', $data->kode_coa);
					if($data->satuan == 'IDR'){
						$total_p_idr_realisasi += $data->nominal * $data->jumlah;
					}else if($data->satuan == 'USD'){
						$total_p_usd_realisasi += $data->nominal * $data->jumlah;
					}
					?>
					<tr align="center">
						<td><?php echo e(++$no_biaya_info); ?></td>
						<td><?php echo e($kode_coa[0]); ?></td>
						<?php if($trxMaster->luar_dalam == 'ca_dalam'): ?>
						<td align="left"><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_dalam->propinsi); ?>) <?php endif; ?></td>
						<?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?>
						<td align="left"><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_luar->negara); ?>) <?php endif; ?></td>
						<?php endif; ?>
						<td><?php echo e($data->jumlah); ?></td>
						<td align="right"><?php echo e(number_format($data->nominal, 0)); ?></td>
						<td><?php echo e($data->satuan); ?></td>
						<td align="right"><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td colspan="5" align="right"><strong>Total Biaya Personel</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong><?php echo e(number_format($total_p_idr_realisasi, 0)); ?> / <?php echo e(number_format($total_p_usd_realisasi, 0)); ?></strong></td>
					</tr>
					<tr>
						<td colspan="5" align="right"><strong>Cash Advance</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong><?php echo e(number_format($total_p_idr, 0)); ?> / <?php echo e(number_format($total_p_usd, 0)); ?></strong></td>
					</tr>
					<tr>
						<td colspan="5" align="right"><strong>Diterima / Dikembalikan</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong><?php echo e(number_format($total_p_idr-$total_p_idr_realisasi, 0)); ?> / <?php echo e(number_format($total_p_usd-$total_p_usd_realisasi, 0)); ?></strong></td>
					</tr>
				</table>
				<?php endif; ?>
				<!--  -->
				<?php if(count($trxDetailBiaya)>0): ?>
					<?php $__currentLoopData = $trxDetailBiaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					if($data->satuan == 'IDR'){
						$total_l_idr += $data->nominal * $data->jumlah;
					}else if($data->satuan == 'USD'){
						$total_l_usd += $data->nominal * $data->jumlah;
					}
					?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
				<?php if(count($trxDetailBiayaRealisasi)>0): ?>
				<strong>&nbsp;Biaya Lain-lain</strong><br/>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%">#</td>
						<td width="10%">Kode COA</td>
						<td width="40%">Keterangan</td>
						<td width="5%">Qty</td>
						<td width="15%">Nominal</td>
						<td width="10%">Satuan</td>
						<td width="15%">Total</td>
					</tr>
					<?php $__currentLoopData = $trxDetailBiayaRealisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					$kode_coa = explode('-', $data->kode_coa);
					if($data->satuan == 'IDR'){
						$total_l_idr_realisasi += $data->nominal * $data->jumlah;
					}else if($data->satuan == 'USD'){
						$total_l_usd_realisasi += $data->nominal * $data->jumlah;
					}
					?>
					<tr align="center">
						<td><?php echo e(++$no_biaya); ?></td>
						<?php if($trxMaster->status_id == 18): ?>
						<td>&nbsp;</td>
						<td align="left"><?php echo e($data->biaya); ?> <?php if($data->is_ca==1): ?> (Realisasi) <?php elseif($data->is_ca==2): ?> (Reimbursement) <?php endif; ?></td>
						<?php else: ?>
						<td><?php echo e($kode_coa[0]); ?></td>
						<td align="left"><?php echo e($kode_coa[1]); ?> <?php if($data->is_ca==1): ?> (Realisasi) <?php elseif($data->is_ca==2): ?> (Reimbursement) <?php endif; ?></td>
						<?php endif; ?>
						<td><?php echo e($data->jumlah); ?></td>
						<td align="right"><?php echo e(number_format($data->nominal, 0)); ?></td>
						<td><?php echo e($data->satuan); ?></td>
						<td align="right"><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td colspan="5" align="right"><strong>Total Biaya Lain-lain</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong><?php echo e(number_format($total_l_idr_realisasi, 0)); ?> / <?php echo e(number_format($total_l_usd_realisasi, 0)); ?></strong></td>
					</tr>
					<tr>
						<td colspan="5" align="right"><strong>Cash Advance</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong><?php echo e(number_format($total_l_idr, 0)); ?> / <?php echo e(number_format($total_l_usd, 0)); ?></strong></td>
					</tr>
					<tr>
						<td colspan="5" align="right"><strong>Diterima / Dikembalikan</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong><?php echo e(number_format($total_l_idr-$total_l_idr_realisasi, 0)); ?> / <?php echo e(number_format($total_l_usd-$total_l_usd_realisasi, 0)); ?></strong></td>
					</tr>
				</table>
				<?php endif; ?>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="75%" colspan="5" align="right"><strong>Total Realisasi</strong></td>
						<td width="10%" align="center"><strong>IDR / USD</strong></td>
						<td width="15%" align="right"><strong><?php echo e(number_format($total_p_idr_realisasi+$total_l_idr_realisasi, 0)); ?> / <?php echo e(number_format($total_p_usd_realisasi+$total_l_usd_realisasi, 0)); ?></strong></td>
					</tr>
					<tr>
						<td width="75%" colspan="5" align="right"><strong>Total Cash Advance</strong></td>
						<td width="10%" align="center"><strong>IDR / USD</strong></td>
						<td width="15%" align="right"><strong><?php echo e(number_format($total_p_idr+$total_l_idr, 0)); ?> / <?php echo e(number_format($total_p_usd+$total_l_usd, 0)); ?></strong></td>
					</tr>
					<tr>
						<td width="75%" colspan="5" align="right"><strong>Total Biaya Diterima / Dikembalikan</strong></td>
						<td width="10%" align="center"><strong>IDR / USD</strong></td>
						<td width="15%" align="right"><strong><?php echo e(number_format($total_p_idr+$total_l_idr-$total_p_idr_realisasi-$total_l_idr_realisasi, 0)); ?> / <?php echo e(number_format($total_p_usd+$total_l_usd-$total_p_usd_realisasi-$total_l_usd_realisasi, 0)); ?></strong></td>
					</tr>
				</table>
				<?php echo $htmlLog; ?>

			</td>
		</tr>
	</table>
	<!-- finish -->
</body>
</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/bpd/pdf_realisasi.blade.php ENDPATH**/ ?>