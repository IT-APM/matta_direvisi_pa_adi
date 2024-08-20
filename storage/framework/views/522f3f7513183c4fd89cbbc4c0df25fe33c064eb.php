<?php $__env->startSection('title', 'Formulir Permohonan Cuti'); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Cuti</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Tipe Cuti</td>
                                            <td width="60%"><?php echo e($trxCuti->_tipe->tipe_cuti); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tahun Periode Cuti</td>
                                            <td><?php echo e($trxCuti->periode_cuti); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mengajukan cuti pada</td>
                                            <td><?php echo e($arrTanggal['tanggal_mulai']); ?><br />sampai
                                                dengan<br /><?php echo e($arrTanggal['tanggal_akhir']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah hari cuti</td>
                                            <td><?php echo e($trxCuti->jumlah_hari); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal cuti</td>
                                            <td>
                                                <?php $__currentLoopData = $arrTanggal['arrTanggalCuti']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($value); ?><br />
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Masuk bekerja kembali</td>
                                            <td><?php echo e($arrTanggal['tanggal_masuk']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alasan pengajuan cuti</td>
                                            <td><?php echo $trxCuti->alasan; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td><?php echo $trxCuti->alamat; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nomor telepon</td>
                                            <td><?php echo e($trxCuti->telepon); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td><?php echo $trxCuti->keterangan; ?></td>
                                        </tr>
                                        <?php
                                        $where = ['id_trx' => $trxCuti->id];
                                        $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
                                        ?>
                                        <tr>
                                            <td>Catatan Atasan</td>
                                            <td><?php if(!empty($catatan)): ?><?php echo e($catatan->keterangan); ?><?php endif; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 notification box-col-6">
                    <div class="card height-equal">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex">
                                    <?php if($loop->first): ?>
                                    <div class="activity-dot-success"></div>
                                    <?php elseif($loop->last): ?>
                                    <div class="activity-dot-warning"></div>
                                    <?php else: ?>
                                    <div class="activity-dot-primary"></div>
                                    <?php endif; ?>
                                    <div class="ms-3">
                                        <p class="d-flex justify-content-between mb-2"><span
                                                class="date-content light-background"><?php echo e($_history->created_at); ?></span>
                                        </p>
                                        <h6><?php echo e($_history->_status->status_cuti); ?><span class="dot-notification"></span>
                                        </h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                            <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_karyawan.'/'.$trxCuti->nik_karyawan.'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_karyawan.'/'.$trxCuti->nik_karyawan.'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span
                                                        class="f-light m-10"><?php echo e($karyawan['nama']); ?> / <?php echo e($karyawan['nik']); ?><br /><?php echo e($karyawan['jabatan']); ?>

                                                        <br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_atasan.'/'.$trxCuti->nik_atasan.'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_atasan.'/'.$trxCuti->nik_atasan.'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Atasan</h6><span
                                                        class="f-light m-10"><?php echo e($atasan['nama']); ?> / <?php echo e($atasan['nik']); ?><br /><?php echo e($atasan['jabatan']); ?>

                                                        <br /><?php echo e($atasan['divisi']); ?> / <?php echo e($atasan['dep']); ?> / <?php echo e($atasan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php $__currentLoopData = $arrPicProperty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">PIC Pengganti</h6><span
                                                        class="f-light m-10"><?php echo e($value['nama']); ?> / <?php echo e($value['nik']); ?><br /><?php echo e($value['jabatan']); ?>

                                                        <br /><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/view.blade.php ENDPATH**/ ?>