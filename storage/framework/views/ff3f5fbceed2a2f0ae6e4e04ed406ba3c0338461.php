<?php $__env->startSection('title', 'Payroll Master'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll Master</li>
<li class="breadcrumb-item"><?php if($tipe == 'add'): ?> Penambahan <?php else: ?> Pengurangan <?php endif; ?></li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($addsub->id), 'tipe'=>'edit'])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php else: ?>
                    <form action="<?php echo e(route('payroll.upsert_master', ['tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <input type="hidden" id="_id" name="_id" value="<?php echo e($id); ?>">
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nama">Komponen</label>
                                <input name="nama" id="nama" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($addsub->nama); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="bulanan">Bulanan</label>
                                <div class="media-body text-start icon-state switch-outline">
                                    <label class="switch">
                                        <input name="bulanan" id="bulanan" type="checkbox" <?php if($status=='edit' ): ?> <?php if($addsub->bulanan == 1): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state bg-success"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('payroll.master', $id)); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/upsert_addsub.blade.php ENDPATH**/ ?>