<?php $__env->startSection('title', 'Validation Forms'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Mapping</li>
<li class="breadcrumb-item">Project vs Admin</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('mtrdataprojectadmin.update', $trx->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php else: ?>
                    <form action="<?php echo e(route('mtrdataprojectadmin.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="project_id">Project ID</label>
                                <input name="project_id" id="project_id" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($trx->project_id); ?>" <?php endif; ?>>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="name">Project Name</label>
                                <input name="name" id="name" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($trx->name); ?>" <?php endif; ?>>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="divisi">Division</label>
                                <input name="divisi" id="divisi" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($trx->divisi); ?>" <?php endif; ?>>
                            </div>
                        </div>
                        <br/>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="customer">Customer Name</label>
                                <input name="customer" id="customer" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($trx->customer); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="group">Group</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="group" name="group" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <option value="Corporate" <?php if($status=='edit' ): ?> <?php if('Corporate' == $trx->group): ?> selected <?php endif; ?> <?php endif; ?> >Corporate</option>
                                    <option value="Project" <?php if($status=='edit' ): ?> <?php if('Project' == $trx->group): ?> selected <?php endif; ?> <?php endif; ?> >Project</option>
                                    <option value="Project IOH" <?php if($status=='edit' ): ?> <?php if('Project IOH' == $trx->group): ?> selected <?php endif; ?> <?php endif; ?> >Project IOH</option>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="coa_prefix">COA Prefix</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="coa_prefix" name="coa_prefix" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <option value="5" <?php if($status=='edit' ): ?> <?php if('5' == $trx->coa_prefix): ?> selected <?php endif; ?> <?php endif; ?> >5</option>
                                    <option value="6" <?php if($status=='edit' ): ?> <?php if('6' == $trx->coa_prefix): ?> selected <?php endif; ?> <?php endif; ?> >6</option>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-2">
								<label class="form-label" for="is_active">Aktif</label>
                                <div class="media-body text-start icon-state switch-outline">
                                    <label class="switch">
										<input name="is_active" id="is_active" type="checkbox" <?php if($status=='edit' ): ?> <?php if($trx->is_active == 1): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state bg-success"></span>
									</label>
								</div>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('mtrdataprojectadmin.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/bpd/project_admin/upsert.blade.php ENDPATH**/ ?>