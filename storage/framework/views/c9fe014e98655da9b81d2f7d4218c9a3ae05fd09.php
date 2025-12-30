<?php $__env->startSection('title', 'Sertifikasi'); ?>

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
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Sertifikasi</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <?php echo session('error'); ?>

                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('karyawansertifikasi.update', [$trx->id, 'nik'=>request()->nik, 'karyawan_id'=>request()->karyawan_id, 'src'=>request()->src])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php else: ?>
                    <form action="<?php echo e(route('karyawansertifikasi.store', ['nik'=>request()->nik, 'karyawan_id'=>request()->karyawan_id, 'src'=>request()->src])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nama">Nama Sertifikasi</label>
                                <input name="nama" id="nama" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($trx->nama); ?>" <?php endif; ?>>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" ><?php if($status == 'edit'): ?><?php echo e($trx->keterangan); ?><?php endif; ?></textarea>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(url()->previous()); ?>" class="btn btn-warning" type="button">Cancel</a>
                    </form>
                    <?php if($status=='edit' ): ?>
                    <br/>
                    <form action="<?php echo e(route('karyawansertifikasi.destroy', [$trx->id, 'nik'=>request()->nik, 'karyawan_id'=>request()->karyawan_id, 'src'=>request()->src])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to delete this data?');">
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/sertifikasi/upsert.blade.php ENDPATH**/ ?>