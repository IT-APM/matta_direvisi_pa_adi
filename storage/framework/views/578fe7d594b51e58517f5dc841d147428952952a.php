<?php $__env->startSection('title', 'Formulir Karyawan Non Aktif'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Auto Formulir Karyawan Non Aktif</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Auto Formulir Karyawan Non Aktif</li>
<li class="breadcrumb-item active">Add new</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('nonaktif_auto.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label class="form-label" for="nik_karyawan">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> / <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">Karyawan is required.</div>
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <div class="col-sm-4">
                                <label class="form-label">Perusahaan</label>
                                <input class="form-control" value="" id="kode_perusahaan" name="kode_perusahaan">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Atasan</label>
                                <input class="form-control" value="" id="layer1" name="layer1">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Tanggal berakhir</label>
                                <input class="form-control" value="" id="tanggal_berakhir" name="tanggal_berakhir">
                            </div>
                        </div> -->
                        <br />
                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="store">Submit</button> | <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
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
<script type="text/javascript">
$(document).ready(function() {
    $("#nik_karyawan").change(function() {
        var value = $(this).val();
        // $("#layer1").prop("value", value);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/nonaktif/auto/insert.blade.php ENDPATH**/ ?>