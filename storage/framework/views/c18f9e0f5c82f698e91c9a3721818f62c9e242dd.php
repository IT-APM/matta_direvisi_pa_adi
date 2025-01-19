<?php $__env->startSection('title', 'Validation Forms'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Edit</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir Reimbursement</li>
<li class="breadcrumb-item">Detail</li>
<li class="breadcrumb-item active">Edit</li>
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
                    <form action="<?php echo e(route('reimburse.updateDetail', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id))); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('POST'); ?>
                    <?php echo csrf_field(); ?>
                        <input type="hidden" id="trxMId" name="trxMId" value="<?php echo e((new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id_reimburse)); ?>" >
                        <input type="hidden" id="_id_layanan" name="_id_layanan" value="<?php echo e($trxD->id_layanan); ?>" >
                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label class="form-label" for="id_hubungan">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="id_hubungan" name="id_hubungan" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $hubungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trxD->id_hubungan): ?> selected <?php endif; ?> ><?php echo e($data->hubungan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="nama_pasien">Nama Pasien</label>
                                <input name="nama_pasien" id="nama_pasien" class="form-control" type="text" required="" value="<?php echo e($trxD->nama_pasien); ?>" >
                            </div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label class="form-label" for="id_layanan">Layanan</label>
                                <select class="form-select" id="id_layanan" name="id_layanan" required="" disabled>
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $layanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trxD->id_layanan): ?> selected <?php endif; ?> ><?php echo e($data->layanan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal">Tanggal Layanan</label>
                                <input name="tanggal" id="tanggal" class="form-control" type="date" required="" value="<?php echo e($trxD->tanggal); ?>" >
                            </div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label class="form-label" for="jumlah_klaim">Nominal Klaim</label>
                                <input min=0 type="number" name="jumlah_klaim" id="jumlah_klaim" class="form-control numbers" required="" value="<?php echo e($trxD->jumlah_klaim); ?>" >
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('reimburse.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id_reimburse))); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/reimburse/detail/upsert.blade.php ENDPATH**/ ?>