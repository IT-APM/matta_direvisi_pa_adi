<?php $__env->startSection('title', 'Data Master Pekerjaan'); ?>

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
<li class="breadcrumb-item">Pekerjaan</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('pekerjaan_master.update', $trx->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('pekerjaan_master.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row pt-0">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_bidang">Bidang Pekerjaan</label>
                                    <select class="form-select" id="id_bidang" name="id_bidang" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $bidang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id ==
                                            $trx->id_bidang): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->bidang); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="pekerjaan">Pekerjaan</label>
                                    <input name="pekerjaan" id="pekerjaan" class="form-control" type="text" required=""
                                        <?php if($status=='edit' ): ?> value="<?php echo e($trx->pekerjaan); ?>" <?php endif; ?>>
                                </div>
                            </div>
                            <div class="row pt-0">
                                <div class="col-md-8">
                                    <label class="form-label" for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan"
                                        rows="4"><?php if($status == 'edit'): ?> <?php echo e($trx->keterangan); ?> <?php endif; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="row pt-3">
                                <div class="col-md-4">
                                    <button class="btn btn-success" type="submit">Submit</button>
                                    | <a href="<?php echo e(route('pekerjaan_master.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                                </div>
                            </div>
                        </form>
                        
                            <div class="row pt-2">
                                <div class="col-md-4">
                                    <?php if($status == 'edit'): ?>
                                    <form action="<?php echo e(route('pekerjaan_master.destroy', $trx->id)); ?>" method="POST" onsubmit="return confirm('Are you sure want to delete this data?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                    <?php endif; ?>
                                    </div>
                            </div>
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
<script type="text/javascript">

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/pekerjaan/upsert.blade.php ENDPATH**/ ?>