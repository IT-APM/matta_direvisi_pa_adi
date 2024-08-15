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
                        <td width="50%"><div class="center"><strong>FORM REKOMENDASI KARYAWAN</strong></div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trxM->id); ?></div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KARYAWAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Tanggal Bergabung<br/>Status<br/>Level</td>
                        <td width="70%">: <?php echo e($karyawan['nama']); ?><br/>: <?php echo e($karyawan['nik']); ?><br/>:
                            <?php echo e($karyawan['jabatan']); ?><br/>: <?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> /
                            <?php echo e($karyawan['sub_dep']); ?><br/>: <?php if(!empty($karyawan['tanggal_bergabung'])): ?> <?php echo e(Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y')); ?> <?php endif; ?><br/>:  <?php echo e($karyawan['status_karyawan']); ?><br/>: <?php echo e($karyawan['level']); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>REKOMENDASI DARI ATASAN LANGSUNG</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2">Saya yang bertanda tangan di bawah ini :</td>
                    </tr>
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                        <td width="70%">: <?php echo e($atasan['nama']); ?><br/>: <?php echo e($atasan['nik']); ?><br/>:
                            <?php echo e($atasan['jabatan']); ?><br/>: <?php echo e($atasan['divisi']); ?> / <?php echo e($atasan['dep']); ?> /
                            <?php echo e($atasan['sub_dep']); ?></td>
                    </tr>
                    <tr>
                        <td>Mengajukan</td>
                        <td>: <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(++$no); ?>. <?php echo e($value->jenis_pengajuan); ?> 
                                <?php if($value->jenis_pengajuan == 'Insentif'): ?>
                                    <?php echo e($value->sebelum); ?> selama <?php echo e($value->setelah); ?> bulan <br/>&nbsp;
                                <?php elseif($value->jenis_pengajuan == 'Nilai Prosentase Kenaikan Gaji'): ?>
                                    <?php echo e($value->setelah); ?> %<br/>&nbsp;
                                <?php else: ?>
                                    dari <?php echo e($value->sebelum); ?> menjadi <?php echo e($value->setelah); ?> <br/>&nbsp;
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        
                    </tr>
                    <tr>
                        <td colspan="2">Untuk karyawan tersebut di atas terhitung mulai tanggal <?php echo e($trxM->terhitung_tanggal); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>DASAR PENGAJU</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><?php echo $trxM->dasar_pengajuan; ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>CATATAN PENGAJU</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><?php echo $trxM->catatan_pengajuan; ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN APPROVAL-1</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Catatan</td>
                        <td width="70%">: <?php echo e($reviewer1['nama']); ?><br/>: <?php echo e($reviewer1['nik']); ?><br/>:
                            <?php echo e($reviewer1['jabatan']); ?><br/>: <?php echo e($reviewer1['divisi']); ?> / <?php echo e($reviewer1['dep']); ?> /
                            <?php echo e($reviewer1['sub_dep']); ?><br/>: <?php echo $trxM->catatan_reviewer1; ?></td>
                    </tr>
                </table>
                <hr />
                <?php if(!empty($reviewer2)): ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN APPROVAL-2</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Catatan</td>
                        <td width="70%">: <?php echo e($reviewer2['nama']); ?><br/>: <?php echo e($reviewer2['nik']); ?><br/>:
                            <?php echo e($reviewer2['jabatan']); ?><br/>: <?php echo e($reviewer2['divisi']); ?> / <?php echo e($reviewer2['dep']); ?> /
                            <?php echo e($reviewer2['sub_dep']); ?><br/>: <?php echo $trxM->catatan_reviewer2; ?></td>
                    </tr>
                </table>
                <hr />
                <?php endif; ?>
                <?php if(!empty($reviewer3)): ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN APPROVAL-3</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Catatan</td>
                        <td width="70%">: <?php echo e($reviewer3['nama']); ?><br/>: <?php echo e($reviewer3['nik']); ?><br/>:
                            <?php echo e($reviewer3['jabatan']); ?><br/>: <?php echo e($reviewer3['divisi']); ?> / <?php echo e($reviewer3['dep']); ?> /
                            <?php echo e($reviewer3['sub_dep']); ?><br/>: <?php echo $trxM->catatan_reviewer3; ?></td>
                    </tr>
                </table>
                <hr />
                <?php endif; ?>
                <?php if(!empty($rekomendasiLog)): ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>CATATAN HCO</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><?php echo $rekomendasiLog->keterangan; ?></td>
                    </tr>
                </table>
                <hr />
                <?php endif; ?>
                <?php echo $htmlLog; ?>

            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/rekomendasi/pdf.blade.php ENDPATH**/ ?>