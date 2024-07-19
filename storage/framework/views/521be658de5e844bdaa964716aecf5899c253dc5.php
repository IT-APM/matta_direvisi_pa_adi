
<?php $__env->startSection('title', 'Add new'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Jadwal</li>
<li class="breadcrumb-item">Grup CLS 2</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('jadwalcls2.update', $trx->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
					<?php echo method_field('PATCH'); ?>
					<?php else: ?>
					<form action="<?php echo e(route('jadwalcls2.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
					<?php endif; ?>
					<?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $trx->nik): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <br />
                            <div class="col-md-4">
                                <label class="form-label" for="id_shift">Shift</label>
                                <select class="form-select" id="id_shift" name="id_shift" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $shift; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $trx->id_shift): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($data->shift); ?> => <?php echo e($data->clock_in); ?> sd <?php echo e($data->clock_out); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <!-- <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_awal">Tanggal Awal</label>
                                <input class="form-control" name="tanggal_awal" id="tanggal_awal" type="date" required="" <?php if($status=='edit' ): ?> value="<?php echo e($trx->tanggal_awal); ?>" <?php endif; ?>>
                            </div>
                            <br />
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" <?php if($status=='edit' ): ?> value="<?php echo e($trx->tanggal_akhir); ?>" <?php endif; ?>>
                            </div>
                        </div> -->
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('jadwalcls2.show', 0)); ?>" class="btn btn-warning" type="button">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/jadwal/cls2/upsert.blade.php ENDPATH**/ ?>