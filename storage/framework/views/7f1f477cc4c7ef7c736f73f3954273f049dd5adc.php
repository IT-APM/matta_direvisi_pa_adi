<?php $__env->startSection('title', 'Formulir Penilaian Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Edit</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Auto Formulir Penilaian Karyawan</li>
<li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('penilaian_auto.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id))); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to update this data?');">
                    <?php echo method_field('PATCH'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik_karyawan">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $trx->nik_karyawan): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">Karyawan is required.</div>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="id_tipe">Tipe Penilaian</label>
                                <select class="form-select" id="id_tipe" name="id_tipe" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trx->id_tipe): ?> selected <?php endif; ?> ><?php echo e($data->tipe_penilaian); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4" id="div_penilai_1">
                                <label class="form-label" for="penilai_1">Penilai 1</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="penilai_1" name="penilai_1" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $trx->nik_penilai_1): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <br />
                            </div>
                            <div class="col-md-4" id="div_penilai_2">
                                <label class="form-label" for="penilai_2">Penilai 2</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="penilai_2" name="penilai_2">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $trx->nik_penilai_2): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <br />
                            </div>
                            <div class="col-md-4" id="div_penilai_3">
                                <label class="form-label" for="penilai_3">Penilai 3</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="penilai_3" name="penilai_3">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $trx->nik_penilai_3): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Update</button> | <a href="<?php echo e(route('penilaian_auto.index')); ?>" class="btn btn-warning" type="button">Back</a>
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
        // var _url = window.location.origin;
        // $("#penilai_1").prop('disabled', true);
        // $("#penilai_2").prop('disabled', true);
        // $("#penilai_3").prop('disabled', true);
        // $('#div_penilai_1').hide();
        // $('#div_penilai_2').hide();
        // $('#div_penilai_3').hide();
        $("#nik_karyawan").change(function() {
            $('#id_tipe').prop('selectedIndex', 0);
        });
        $("#id_tipe").change(function() {
            var selectedText = $(this).find("option:selected").text();
            var selectedValue = $(this).val();
            var id = $('#nik_karyawan').val();
            // alert("Selected Text: " + selectedText + " Value: " + selectedValue);
            if (selectedValue == 1 || selectedValue == 4 || selectedValue == 5) {
                $("#div_penilai_1").show();
                $('#div_penilai_2').hide();
                $('#div_penilai_3').hide();
                $("#penilai_1").prop('disabled', false);
                $("#penilai_2").prop('disabled', true);
                $("#penilai_3").prop('disabled', true);
                // Empty the dropdown
                $('#penilai_1').find('option').not(':first').remove();
                // AJAX request 
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer1 = response['layer'].layer1;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer1 == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_1").append(option);
                            }
                        }
                    }
                });
            } else {
                $("#penilai_1").prop('disabled', false);
                $("#penilai_2").prop('disabled', false);
                $("#penilai_3").prop('disabled', false);
                $('#div_penilai_1').show();
                $('#div_penilai_2').show();
                $('#div_penilai_3').show();
                // penilai 1
                $('#penilai_1').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer1;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_1").append(option);
                            }
                        }
                    }
                });
                // penilai 2
                $('#penilai_2').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer2;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_2").append(option);
                            }
                        }
                    }
                });
                // penilai 3
                $('#penilai_3').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer3;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_3").append(option);
                            }
                        }
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/auto/insert.blade.php ENDPATH**/ ?>