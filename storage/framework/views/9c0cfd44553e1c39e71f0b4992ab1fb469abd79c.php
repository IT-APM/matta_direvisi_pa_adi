<?php $__env->startSection('title', 'Formulir Reimbursement'); ?>

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
<li class="breadcrumb-item">Formulir Reimbursement</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Plafon</h5>
                </div>
                <div class="card-body typography">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <?php echo session('error'); ?>

                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>Tahun</h6>
                                    </th>
                                    <th>
                                        <h6>Kesehatan</h6>
                                    </th>
                                    <th>
                                        <h6>Frame</h6>
                                    </th>
                                    <th>
                                        <h6>Lensa</h6>
                                    </th>
                                    <th>
                                        <h6>Kehamilan</h6>
                                    </th>
                                    <th>
                                        <h6>Persalinan Normal</h6>
                                    </th>
                                    <th>
                                        <h6>Persalinan Caesar</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo e($plafon->periode); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->kesehatan, 2)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->frame, 2)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->lensa, 2)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->kehamilan, 2)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->persalinan_normal, 2)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->persalinan_caesar, 2)); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="<?php echo e(route('reimburse.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo csrf_field(); ?>
                    <!--  -->
                    <div class="card-header">
                        <h5>Isian Reimbursement</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_reimburse_detail">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Hubungan Dengan Karyawan</h6>
                                                </th>
                                                <th>
                                                    <h6>Nama Pasien</h6>
                                                </th>
                                                <th>
                                                    <h6>Layanan</h6>
                                                </th>
                                                <th>
                                                    <h6>Tanggal Layanan</h6>
                                                </th>
                                                <th>
                                                    <h6>Nominal Klaim</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="reimburse_detail0">
                                                <td>
                                                    <select class="form-select" id="hubungan[]" name="hubungan[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $hubungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->hubungan); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="nama_pasien[]" id="nama_pasien[]" class="form-control" type="text" required="">
                                                </td>
                                                <td>
                                                    <select class="form-select" id="layanan[]" name="layanan[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $layanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->layanan); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" name="tanggal[]" id="tanggal[]" type="date" required="">
                                                </td>
                                                <td>
                                                    <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim[]" id="jumlah_klaim[]" class="form-control numbers" required="">
                                                </td>
                                            </tr>
                                            <tr id="reimburse_detail1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button id="add_row" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id='delete_row' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                        <br/>Catatan khusus baris pertama:<br/>
                        1. Jika kolom Hubungan Dengan Karyawan dipilih Karyawan maka kolom Nama Pasien akan otomatis terisi nama karyawan.
                        <br/>2. Jika kolom Layanan dipilih Kehamilan, Persalinan-Normal atau Persalinan-Caesar maka kolom Nominal Klaim akan otomatis terisi plafon.
                    </div>
                    <!-- <hr/> -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Isian Lampiran</h5>
                            </div>
                            <div class="card-body typography">
                                <div class="table-responsive">
                                    <table class="table" id="trx_reimburse_lampiran">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Lampiran Pengajuan</h6>
                                                </th>
                                                <th>
                                                    <h6>File Lampiran</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="reimburse_lampiran0">
                                                <td>
                                                    <select class="form-select" id="lampiran[]" name="lampiran[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->lampiran); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="file_lampiran[]" id="file_lampiran[]" class="form-control" type="file" required="">
                                                </td>
                                            </tr>
                                            <tr id="reimburse_lampiran1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button id="add_row_lampiran" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id='delete_row_lampiran' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                                <br /><br />
                                Note: Lampiran wajib diisi dan dilengkapi.
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Keterangan</h5>
                            </div>
                            <div class="card-body typography">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-body typography">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
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
<!--  -->
<!-- source: https://blog.quickadminpanel.com/master-detail-form-in-laravel-jquery-create-order-with-products/ -->
<script type="text/javascript">
function use_number(node) {
  var empty_val = false;
  const value = node.value;
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
  if (node.value == '')
    empty_val = true;
  node.type = 'number';
  if (!empty_val)
    node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
}
// 
function use_text(node) {
  var empty_val = false;
  const value = Number(node.value);
  if (node.value == '')
    empty_val = true;
  node.type = 'text';
  if (!empty_val)
    node.value = value.toLocaleString('en');  // or other formatting
}
// 
$(document).ready(function() {
    // 
    let row_number = 1;
    $("#add_row").click(function(e) {
        e.preventDefault();
        let new_row_number = row_number - 1;
        $('#reimburse_detail' + row_number).html($('#reimburse_detail' + new_row_number).html()).find('td:first-child');
        $('#trx_reimburse_detail').append('<tr id="reimburse_detail' + (row_number + 1) + '"></tr>');
        $('#reimburse_detail' + row_number + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
        row_number++;
    });

    $("#delete_row").click(function(e) {
        e.preventDefault();
        if (row_number > 1) {
            $("#reimburse_detail" + (row_number - 1)).html('');
            row_number--;
        }
    });
    // 
    let row_number_lampiran = 1;
    $("#add_row_lampiran").click(function(e) {
        e.preventDefault();
        let new_row_number_lampiran = row_number_lampiran - 1;
        $('#reimburse_lampiran' + row_number_lampiran).html($('#reimburse_lampiran' + new_row_number_lampiran).html()).find('td:first-child');
        $('#trx_reimburse_lampiran').append('<tr id="reimburse_lampiran' + (row_number_lampiran + 1) + '"></tr>');
        row_number_lampiran++;
    });

    $("#delete_row_lampiran").click(function(e) {
        e.preventDefault();
        if (row_number_lampiran > 1) {
            $("#reimburse_lampiran" + (row_number_lampiran - 1)).html('');
            row_number_lampiran--;
        }
    });
    // $("select[id^=hubungan]").change(function() {
    $('select[id="hubungan[]"]').change(function() {
        var selectedValue = $(this).val();
        // alert(selectedValue);
        if (selectedValue == 1) {
            // $("input[id^=nama_pasien]").val('<?php echo e($karyawan->nama); ?>');
            $('input[id="nama_pasien[]"]').first().val('<?php echo e($karyawan->nama); ?>');
        }
    });
    var nama0 = '#reimburse_detail0';
    $(nama0).on('change', 'select[id="layanan[]"]', function () {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $(nama0 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->kehamilan); ?>');
            $(nama0 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 5) {
            $(nama0 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_normal); ?>');
            $(nama0 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 6) {
            $(nama0 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_caesar); ?>');
            $(nama0 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else{
            $(nama0 + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
            $(nama0 + ' input[id="jumlah_klaim[]"]').val();
        }
    });
    var nama1 = '#reimburse_detail1';
    $(nama1).on('change', 'select[id="layanan[]"]', function () {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $(nama1 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->kehamilan); ?>');
            $(nama1 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 5) {
            $(nama1 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_normal); ?>');
            $(nama1 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 6) {
            $(nama1 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_caesar); ?>');
            $(nama1 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else{
            $(nama1 + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
            $(nama1 + ' input[id="jumlah_klaim[]"]').val();
        }
    });
    var nama2 = '#reimburse_detail2';
    $(nama2).on('change', 'select[id="layanan[]"]', function () {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $(nama2 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->kehamilan); ?>');
            $(nama2 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 5) {
            $(nama2 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_normal); ?>');
            $(nama2 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 6) {
            $(nama2 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_caesar); ?>');
            $(nama2 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else{
            $(nama2 + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
            $(nama2 + ' input[id="jumlah_klaim[]"]').val();
        }
    });
    var nama3 = '#reimburse_detail3';
    $(nama3).on('change', 'select[id="layanan[]"]', function () {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $(nama3 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->kehamilan); ?>');
            $(nama3 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 5) {
            $(nama3 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_normal); ?>');
            $(nama3 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 6) {
            $(nama3 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_caesar); ?>');
            $(nama3 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else{
            $(nama3 + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
            $(nama3 + ' input[id="jumlah_klaim[]"]').val();
        }
    });
    var nama4 = '#reimburse_detail4';
    $(nama4).on('change', 'select[id="layanan[]"]', function () {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $(nama4 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->kehamilan); ?>');
            $(nama4 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 5) {
            $(nama4 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_normal); ?>');
            $(nama4 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else if (selectedValue == 6) {
            $(nama4 + ' input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_caesar); ?>');
            $(nama4 + ' input[id="jumlah_klaim[]"]').attr('readonly', true);
        }else{
            $(nama4 + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
            $(nama4 + ' input[id="jumlah_klaim[]"]').val();
        }
    });
    // $('select[id="layanan[]"]').change(function() {
    //     var selectedValue = $(this).val();
    //     // alert(selectedValue);
    //     if (selectedValue == 4) {
    //         $('input[id="jumlah_klaim[]"]').first().val('<?php echo e($plafon->kehamilan); ?>');
    //         $('input[id="jumlah_klaim[]"]').first().attr('readonly', true);
    //     }else if (selectedValue == 5) {
    //         $('input[id="jumlah_klaim[]"]').first().val('<?php echo e($plafon->persalinan_normal); ?>');
    //         $('input[id="jumlah_klaim[]"]').first().attr('readonly', false);
    //     }else if (selectedValue == 6) {
    //         $('input[id="jumlah_klaim[]"]').first().val('<?php echo e($plafon->persalinan_caesar); ?>');
    //     }else{
    //         $('input[id="jumlah_klaim[]"]').first().attr('readonly', false);
    //         $('input[id="jumlah_klaim[]"]').first().val();
    //     }
    // });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/reimburse/insert.blade.php ENDPATH**/ ?>