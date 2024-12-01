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
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENILAIAN KARYAWAN</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($data['id_data']); ?></div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>EVALUASI KARYAWAN (<?php echo e($data['tipe']); ?>)</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama Karyawan<br />
                            NIP<br />
                            Jabatan<br />
                            Direktorat / Divisi / Departemen<br />
                            Perusahaan<br />
                            Nama Atasan Langsung<br />
                            Tanggal Bergabung<br />
                            Tanggal Berakhir</td>
                        <td width="70%">: <?php echo e($karyawan['nama']); ?><br />
                            : <?php echo e($karyawan['nik']); ?><br />
                            : <?php echo e($karyawan['jabatan']); ?><br />
                            : <?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?><br />
                            : <?php echo e($karyawan['perusahaan']); ?><br />
                            : <?php echo e($penilai1['nama']); ?><br />
                            : <?php if(!empty($karyawan['tanggal_bergabung'])): ?> <?php echo e(Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y')); ?> <?php endif; ?><br />
                            : <?php if(!empty($karyawan['tanggal_berakhir'])): ?> <?php echo e(Carbon\Carbon::parse($karyawan['tanggal_berakhir'])->format('d-m-Y')); ?> <?php endif; ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PARA PENILAI</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <?php echo $para_penilai; ?>

                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>HASIL PENILAIAN</strong></td>
                    </tr>
                </table>
                <?php if($data['id_tipe'] == 6): ?>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="80%"><strong>Obyek Penilaian</strong></td>
                        <td width="20%" align="center"><strong>Penilai 1</strong></td>
                    </tr>
                    <?php $__currentLoopData = $angka_skor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($_data->id_nilai3); ?>. <?php echo e($_data->penilaian_1); ?></td>
                        <td align="center"><?php echo e($_data->skor_penilai_1); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($angka_rata>0): ?>
                    <tr>
                        <td align="right"><strong>Total Score</strong></td>
                        <td align="center"><strong><?php echo e($angka_rata); ?></strong></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <?php if(count($angka_skor)==14): ?>
                            <td align="center"><strong>Result: <?php if($angka_rata>35): ?> Extend <?php else: ?> Not Extend <?php endif; ?></strong></td>
                        <?php else: ?>
                            <td align="center"><strong>Result: <?php if($angka_rata>40): ?> Extend <?php else: ?> Not Extend <?php endif; ?></strong></td>
                        <?php endif; ?>
                    </tr>
                    <?php endif; ?>
                </table>
                <?php else: ?>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="70%"><strong>Obyek Penilaian</strong></td>
                        <td width="15%" align="center"><strong>Karyawan</strong></td>
                        <td width="15%" align="center"><strong>Penilai 1</strong></td>
                    </tr>
                    <?php echo $angka_skor; ?>

                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <?php echo $angka_total; ?>

                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <?php echo $angka_rata; ?>

                </table>
                <?php endif; ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KESIMPULAN DAN REKOMENDASI</strong></td>
                    </tr>
                </table>
                <hr />
                <?php echo $isian_kes_rek; ?>

                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>Status Perpanjangan: <?php echo e($data['perpanjang']); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                        <?php echo $timestamp; ?>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/pdf.blade.php ENDPATH**/ ?>