<?php $__env->startSection('title', 'Formulir Izin'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Izin</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            <div class="card-header">
                                <h5>Karyawan</h5><br/>
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%"><?php echo e($karyawan['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td><?php echo e($karyawan['nik']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td><?php echo e($karyawan['jabatan']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td><?php echo e($karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']); ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-header">
                                <h5>Layer 1</h5><br/>
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%"><?php echo e($atasan['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td><?php echo e($atasan['nik']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td><?php echo e($atasan['jabatan']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td><?php echo e($atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep']); ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php if(!empty($layer2)): ?>
                            <div class="card-header">
                                <h5>Layer 2</h5><br/>
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%"><?php echo e($layer2['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td><?php echo e($layer2['nik']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td><?php echo e($layer2['jabatan']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td><?php echo e($layer2['divisi'] . '/' . $layer2['dep'] . '/' . $layer2['sub_dep']); ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Izin</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Tipe Izin</td>
                                            <td width="60%"><?php echo e($trx->_tipe->tipe); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td><?php echo e(App\Http\Controllers\TrxIjinController::getTanggalIndonesia($trx->tanggal)); ?></td>
                                        </tr>
                                        <?php if($trx->tipe == 1 || $trx->tipe == 2): ?>
                                        <tr>
                                            <td>Jam Mulai</td>
                                            <td><?php echo e($trx->jam_awal); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jam Selesai</td>
                                            <td><?php echo e($trx->jam_akhir); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td>Alasan</td>
                                            <td><?php echo $trx->alasan; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 notification box-col-4">
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
                                        <h6><?php echo e($_history->_status->status); ?><span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                            <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- finish -->
            </div>
            <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
            <p>&nbsp;</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/izin/view.blade.php ENDPATH**/ ?>