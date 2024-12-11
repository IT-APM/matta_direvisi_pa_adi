<?php $__env->startSection('title', 'FinDim Departement'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">FinDim Departement</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('findim_departemen.update', $findim_departemen->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('findim_departemen.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="departemen_id">No Departement</label>
                                    <input name="departemen_id" id="departemen_id" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($findim_departemen->departemen_id); ?>" <?php endif; ?>>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="departemen_desc">Departement Description</label>
                                    <input name="departemen_desc" id="departemen_desc" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($findim_departemen->departemen_desc); ?>" <?php endif; ?>>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('findim_departemen.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/findim/departemen/upsert.blade.php ENDPATH**/ ?>