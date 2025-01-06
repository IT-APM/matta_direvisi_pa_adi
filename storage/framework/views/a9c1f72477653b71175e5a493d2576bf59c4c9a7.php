<?php $__env->startSection('title', 'Formulir Permohonan Cuti'); ?>

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
<li class="breadcrumb-item">Permohonan Cuti</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="<?php echo e(asset('assets/images/other-images/sad.png')); ?>" alt="error"> -->
                        <h4>ERROR:</h4>
                        <?php if(session('error')[0]['status']): ?> <h5>
                            <?php echo e(session('error')[0]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')[0]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')[1]['status']): ?> <h5>
                            <?php echo e(session('error')[1]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')[1]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')[2]['status']): ?> <h5>
                            <?php echo e(session('error')[2]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')[2]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')[3]['status']): ?> <h5>
                            <?php echo e(session('error')[3]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')[3]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')[4]['status']): ?> <h5>
                            <?php echo e(session('error')[4]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')[4]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')[5]['status']): ?> <h5>
                            <?php echo e(session('error')[5]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')[5]['message']); ?> - NOK</h5> <?php endif; ?>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <form target="_self" id="form" name="form" action="<?php echo e(route('cuti.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Sisa Jumlah Hari Cuti Tahunan</label>
                                
                                
                                <?php if(\Carbon\Carbon::today()->between(\Carbon\Carbon::now()->year.'-01-01', \Carbon\Carbon::now()->year.'-01-31')): ?>
                                    <h4><?php echo e(\Carbon\Carbon::now()->year); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaFormCuti(auth()->user()->nik, \Carbon\Carbon::now()->year)); ?> hari
                                    <br/><?php echo e(\Carbon\Carbon::now()->subYear()->year); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaFormCuti(auth()->user()->nik, \Carbon\Carbon::now()->subYear()->year)); ?> hari</h4>
                                
                                <?php else: ?>
                                    <h4><?php echo e(\Carbon\Carbon::now()->year); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaFormCuti(auth()->user()->nik, \Carbon\Carbon::now()->year)); ?> Hari</h4>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tipe_cuti">Tipe Cuti</label>
                                <select class="form-select" id="tipe_cuti" name="tipe_cuti" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->tipe_cuti); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4" id="div_jumlah_hari">
                                <label class="form-label" for="jumlah_hari">Jumlah Hari</label>
                                <input id="jumlah_hari" name="jumlah_hari" min="1" oninput="validity.valid||(value='');" required="" class="form-control numbers" type="number" placeholder="Jumlah Hari">
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                                <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="pic_pengganti">PIC Pengganti</label>
                                <select class="js-example-basic-multiple col-sm-12" id="pic_pengganti[]" name="pic_pengganti[]" required="" multiple="multiple">
                                    <!-- <option selected="" disabled="" value="">Pilih...</option> -->
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback text-danger">Required</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="telepon">Telepon Pengaju</label>
                                <input class="form-control m-input digits" type="tel" name="telepon" id="telepon" required="">
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="alasan">Alasan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="alamat">Alamat Tujuan Cuti</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                            </div>
                        </div>
                        <br />
                        <button id="submit" name="submit" value="submit" class="btn btn-success" type="submit">Submit</button> | <button class="btn btn-primary" type="submit" id="preview" name="preview" value="preview">Preview</button> | <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
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
    $("#jumlah_hari").prop('disabled', true);
    $('#div_jumlah_hari').hide();
    $("#tipe_cuti").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 0) {
            $("#jumlah_hari").prop('disabled', false);
            $('#div_jumlah_hari').show();
        } else {
            $("#jumlah_hari").prop('disabled', true);
            $('#div_jumlah_hari').hide();
        }
    });
    $('form#form #submit').click(function() {
        $('form#form').attr('target', '_self');
    });
    $('form#form #preview').click(function() {
        $('form#form').attr('target', '_blank');
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/insert.blade.php ENDPATH**/ ?>