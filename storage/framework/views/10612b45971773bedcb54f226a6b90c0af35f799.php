<?php $__env->startSection('title', 'Formulir Karyawan Non Aktif'); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Karyawan Non Aktif</li>
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
                                <h5>Data Karyawan</h5>
                            </div>
                            <div class="card-header">
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%"><?php echo e($pengaju['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td><?php echo e($pengaju['nik']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td><?php echo e($pengaju['jabatan']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td><?php echo e($pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep']); ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Non Aktif</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Alasan Non Aktif</td>
                                            <td width="60%"><?php echo e($trxM->_alasan->alasan); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Bergabung</td>
                                            <td><?php if(!empty($karyawan_properties->tanggal_bergabung)): ?> <?php echo e(\Carbon\Carbon::parse($karyawan_properties->tanggal_bergabung)->format('d-m-Y')); ?> <?php endif; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Berakhir</td>
                                            <td><?php if(!empty($trxM->tanggal)): ?> <?php echo e(\Carbon\Carbon::parse($trxM->tanggal)->format('d-m-Y')); ?> <?php endif; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td><?php echo $trxM->keterangan; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Serah Terima</h5>
                            </div>
                            <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card-body typography">
                                <?php if($value->_into_ == 'di'): ?>
                                <h6>Direktorat / Divisi / Departemen</h6>
                                <?php elseif($value->_into_ == 'ga'): ?>
                                <h6>General Affairs</h6>
                                <?php elseif($value->_into_ == 'it'): ?>
                                <h6>IT</h6>
                                <?php elseif($value->_into_ == 'hc'): ?>
                                <h6>Human Capital</h6>
                                <?php elseif($value->_into_ == 'ko'): ?>
                                <h6>Koperasi Karyawan</h6>
                                <?php endif; ?>
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Obyek</td>
                                            <td width="70%"><?php echo e($value->_obyek->obyek); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td><?php echo $value->keterangan; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div><a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
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
                                        <h6><?php echo e($_history->_status->status); ?><span class="dot-notification"></span>
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
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/nonaktif/view.blade.php ENDPATH**/ ?>