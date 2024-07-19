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

<!-- start -->

<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center"><strong>FORM PENGAJUAN PELATIHAN</strong></div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trx->id); ?></div></td>
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
                        <td width="70%">: <?php echo e($pengaju['nama']); ?><br />: <?php echo e($pengaju['nik']); ?><br />:
                            <?php echo e($pengaju['jabatan']); ?><br />: <?php echo e($pengaju['divisi']); ?> / <?php echo e($pengaju['dep']); ?> /
                            <?php echo e($pengaju['sub_dep']); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PESERTA</strong></td>
                    </tr>
                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="30%">Nama / NIP</th>
                        <th width="45%">Direktorat / Divisi / Departemen</th>
                        <th width="25%">Jabatan</th>
                    </tr>
                    <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($value['nama']); ?> / <?php echo e($value['nik']); ?></td>
                        <td><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></td>
                        <td><?php echo e($value['jabatan']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PELATIHAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Pelatihan ke</td>
                        <td width="70%">: <?php echo e($trx->ke); ?></td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td>: <?php echo e($trx->tahun); ?></td>
                    </tr>
                    <tr>
                        <td>Jenis</td>
                        <td> :
                            <?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($value->jenis); ?> <?php if(!$loop->last): ?> / <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Judul</td>
                        <td>: <?php echo e($trx->judul); ?></td>
                    </tr>
                    <tr>
                        <td>Penyelenggara</td>
                        <td>: <?php echo e($trx->penyelenggara); ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Pelaksanaan</td>
                        <td>: <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y')); ?>

                            sampai
                            <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y')); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>Tempat</td>
                        <td>: <?php echo e($trx->tempat); ?></td>
                    </tr>
                    <tr>
                        <td>Biaya</td>
                        <td>: Rp. <?php echo e(number_format($trx->biaya, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Benefit</td>
                        <td>: 
                            <?php $__currentLoopData = $arrBenefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($value->benefit); ?> <?php if(!$loop->last): ?> / <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Anggaran</td>
                        <td>: <?php echo e($trx->_anggaran->anggaran); ?></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: <?php echo $trx->keterangan; ?></td>
                    </tr>
                </table>
                <hr />
                <?php echo $htmlLog; ?>

            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/pdf.blade.php ENDPATH**/ ?>