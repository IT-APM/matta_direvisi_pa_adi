<?php $__env->startSection('title', 'Validation Forms'); ?>

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
<li class="breadcrumb-item">Departemen</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                        <?php if($status == 'edit'): ?>
                        <form action="<?php echo e(route('subdepartemen.update', $subdepartemen->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('subdepartemen.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="kode_perusahaan">Perusahaan</label>
                                    <select class="form-select" id="kode_perusahaan" name="kode_perusahaan" required="">
                                        <option selected="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $subdepartemen->kode_perusahaan): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="kode_divisi">Direktorat</label>
                                    <select class="form-select" id="kode_divisi" name="kode_divisi" required="">
                                        <option selected="" value="">Pilih...</option>
                                        <?php if($status=='edit' ): ?> 
                                        <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" <?php if($data->id == $subdepartemen->kode_divisi): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="kode_divisi">Divisi</label>
                                    <select class="form-select" id="kode_departemen" name="kode_departemen" required="">
                                        <option selected="" value="">Pilih...</option>
                                        <?php if($status=='edit' ): ?> 
                                        <?php $__currentLoopData = $departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" <?php if($data->id == $subdepartemen->kode_departemen): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="nama">Departemen</label>
                                    <input name="nama" id="nama" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($subdepartemen->nama); ?>" <?php endif; ?>>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="is_active">Aktif</label>
                                    <div class="media-body text-start icon-state switch-outline">
                                        <label class="switch">
                                            <input name="is_active" id="is_active" type="checkbox" <?php if($status=='edit' ): ?> <?php if($subdepartemen->is_active == 1): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('subdepartemen.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<script type="text/javascript">
$(document).ready(function() {
    var _kode_perusahaan = 0;
    var _kode_divisi = 0;
    var _kode_departemen = 0;
    // 
    $("#kode_perusahaan").change(function() {
        var _kode_perusahaan = $(this).val();
        $('#kode_divisi').find('option').not(':first').remove();
        $('#kode_departemen').find('option').not(':first').remove();
        // AJAX request 
        $.ajax({
            url: "<?php echo e(route('get_divisi')); ?>"+"/"+_kode_perusahaan,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var nama = response[i]['nama'];
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_divisi").append(option);
                    }
                }
            }
        });
    });
    // 
    $("#kode_divisi").change(function() {
        var _kode_divisi = $(this).val();
        $('#kode_departemen').find('option').not(':first').remove();
        $.ajax({
            url: "<?php echo e(route('get_departemen')); ?>"+"/"+_kode_divisi,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var nama = response[i]['nama'];
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_departemen").append(option);
                    }
                }
            }
        });
    });
    // 
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/sub_departemen/upsert.blade.php ENDPATH**/ ?>