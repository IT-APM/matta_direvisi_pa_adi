<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Add</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir Pengajuan Pelatihan</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <form action="<?php echo e(route('pelatihan.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="peserta[]">Peserta*</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="peserta[]" name="peserta[]" required="">
                                        <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback txt-danger">Peserta is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tahun">Tahun*</label>
                                    <input min=0 type="number" name="tahun" id="tahun" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="jenis[]">Jenis Pelatihan*</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="jenis[]" name="jenis[]" required="">
                                        <?php $__currentLoopData = $jenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->jenis); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback txt-danger">Jenis Pelatihan is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="judul">Judul Pelatihan*</label>
                                    <input name="judul" id="judul" class="form-control" type="text" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="penyelenggara">Penyelenggara*</label>
                                    <input name="penyelenggara" id="penyelenggara" class="form-control" type="text" required="">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="tanggal_mulai">Tanggal Mulai*</label>
                                        <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="">
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="tanggal_akhir">Tanggal Akhir*</label>
                                        <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="reviewer1">Reviewer 1</label>
                                        <select class="form-select col-sm-12 js-example-basic-single" id="reviewer1" name="reviewer1">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $reviewer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="reviewer2">Reviewer 2</label>
                                        <select class="form-select col-sm-12 js-example-basic-single" id="reviewer2" name="reviewer2">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $reviewer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="anggaran">Anggaran*</label>
                                    <select class="form-select col-sm-12" id="anggaran" name="anggaran" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $anggaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->anggaran); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="biaya">Biaya (Termasuk PPN, jika ada)*</label>
                                    <input min=0 type="number" name="biaya" id="biaya" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="benefit[]">Benefit*</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit[]" name="benefit[]" required="">
                                        <?php $__currentLoopData = $benefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->benefit); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback txt-danger">Benefit is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tempat">Tempat*</label>
                                    <textarea class="form-control" id="tempat" name="tempat" rows="4" required=""></textarea>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <div class="col-md-12">
                            <div class="card-body">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-warning" type="button">Back</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--  -->
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
    // default semua hide dan disable=true
    div_waktu_kontrak(0);
    div_kota(0);
    div_jurusan_pendidikan(0);
    // 1 = Karyawan Kontrak Alita
    $("#id_status_kerja").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 1 || selectedValue == 9) {
            div_waktu_kontrak(1);
        }else{
            div_waktu_kontrak(0);}
    });
    // 1 = Head Office
    $("#id_area").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue > 1) {
            div_kota(1);
        }else{
            div_kota(0);}
    });
    // 
    $("#id_jenjang_pendidikan").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue > 3 && selectedValue < 11) {
            div_jurusan_pendidikan(1);
        }else{
            div_jurusan_pendidikan(0);}
    });
    // 
    function div_waktu_kontrak(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_waktu_kontrak').show();
            $("#waktu_kontrak").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_waktu_kontrak').hide();
            $("#waktu_kontrak").prop('disabled', true);
        }
    }
    // 
    function div_kota(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_kota').show();
            $("#id_kota").prop('disabled', false);
            $('#id_kota').find('option').remove();
            $("#id_kota").append('<option selected="" disabled="" value="">Pilih...</option>');
            var id = $("#id_area").val();
            $.ajax({
                // url: _url + '/matta/kebutuhan/kota/' + id,
                url: "<?php echo e(route('kebutuhan_kota')); ?>" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    // alert(response);
                    var len = 0;
                    if (response['kota'] != null) {
                        len = response['kota'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['kota'][i].id;
                            var kota = response['kota'][i].kota;
                            var option = "<option value='" + id + "'>" + kota + "</option>";
                            $("#id_kota").append(option);
                        }
                    }
                }
            });
        }
        // jika 0 maka non aktif
        else {
            $('#div_kota').hide();
            $("#id_kota").prop('disabled', true);
        }
    }
    // 
    function div_jurusan_pendidikan(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_jurusan_pendidikan').show();
            $("#id_jurusan_pendidikan").prop('disabled', false);
            $("#nilai_ipk").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_jurusan_pendidikan').hide();
            $("#id_jurusan_pendidikan").prop('disabled', true);
            $("#nilai_ipk").prop('disabled', true);
        }
    }
    // 
                // Empty the dropdown
                $('#layer1').find('option').not(':first').remove();
                $('#layer2').find('option').not(':first').remove();
                // karyawan masih di hardcode
                // Andika Cahya Fajar. R
                var id = '01031406353';
                // AJAX request 
                // layer1
                $.ajax({
                    // url: _url + '/matta/kebutuhan/layer/' + id,
                    url: "<?php echo e(route('kebutuhan_layer')); ?>" + "/" + id,
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
                                $("#layer1").append(option);
                            }
                        }
                    }
                });
                // layer2
                $.ajax({
                    // url: _url + '/matta/kebutuhan/layer/' + id,
                    url: "<?php echo e(route('kebutuhan_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer2 = response['layer'].layer2;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer2 == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#layer2").append(option);
                            }
                        }
                    }
                });
    // 
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/insert.blade.php ENDPATH**/ ?>