<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<!-- Daterangepicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
/* Ganti warna untuk tanggal yang dipilih */
.daterangepicker td.in-range {
    background-color: #d3d3d3 !important; /* abu-abu terang */
    color: #000 !important;
}

.daterangepicker td.active,
.daterangepicker td.active:hover {
    background-color: #a9a9a9 !important; /* abu-abu tua */
    color: #fff !important;
}

/* Hover di kalender */
.daterangepicker td.available:hover {
    background-color: #c0c0c0 !important; /* abu-abu medium */
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Sertifikat</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload image is ERROR</h6>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('pelatihan_sertifikat.update', $trx->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php else: ?>
                    <form action="<?php echo e(route('pelatihan_sertifikat.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="nama">Nama</label>
                            <input name="nama" id="nama" class="form-control" type="text" required="" <?php if($status=='edit'): ?> value="<?php echo e($trx->nama); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="nik">NIP</label>
                            <input name="nik" id="nik" class="form-control" type="text" <?php if($status=='edit'): ?> value="<?php echo e($trx->nik); ?>" readonly <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="jenis">Jenis Pelatihan</label>
                            <select class="form-select col-sm-12" id="jenis" name="jenis" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $jenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->jenis); ?>" <?php if($status=='edit' ): ?> <?php if($data->jenis == $trx->jenis): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->jenis); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="int_ext">Mode Pelatihan</label>
                            <select class="form-select col-sm-12" id="int_ext" name="int_ext" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $int_ext; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if($status=='edit'): ?> <?php if($value == $trx->int_ext): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="penerbit">Penerbit</label>
                            <select class="form-select col-sm-12" id="penerbit" name="penerbit" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $penerbit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->penerbit); ?>" <?php if($status=='edit' ): ?> <?php if($data->penerbit == $trx->penerbit): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->penerbit); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                            
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="kta">Kartu Tanda Anggota</label>
                            <select class="form-select col-sm-12" id="kta" name="kta" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $kta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if($status=='edit'): ?> <?php if($value == $trx->kta): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="judul">Judul Pelatihan</label>
                            <input name="judul" id="judul" class="form-control" type="text" required="" <?php if($status=='edit'): ?> value="<?php echo e($trx->judul); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="lembaga">Lembaga</label>
                            <input name="lembaga" id="lembaga" class="form-control" type="text" required="" <?php if($status=='edit'): ?> value="<?php echo e($trx->lembaga); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="nomor_sertifikat">No Sertifikat</label>
                            <input name="nomor_sertifikat" id="nomor_sertifikat" class="form-control" type="text" <?php if($status=='edit'): ?> value="<?php echo e($trx->nomor_sertifikat); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="no_kartu_lisensi">No Kartu Lisensi</label>
                            <input name="no_kartu_lisensi" id="no_kartu_lisensi" class="form-control" type="text" <?php if($status=='edit'): ?> value="<?php echo e($trx->no_kartu_lisensi); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="no_kartu_anggota">No Kartu Anggota</label>
                            <input name="no_kartu_anggota" id="no_kartu_anggota" class="form-control" type="text" <?php if($status=='edit'): ?> value="<?php echo e($trx->no_kartu_anggota); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="biaya">Biaya</label>
                            <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="biaya" id="biaya" class="form-control numbers" required="" <?php if($status=='edit'): ?> value="<?php echo e($trx->biaya); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                            <input class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai" placeholder="Tanggal Mulai" <?php if($status=='edit'): ?> value="<?php echo e($trx->tanggal_mulai); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                            <input class="form-control" type="date" name="tanggal_akhir" id="tanggal_akhir" placeholder="Tanggal Akhir" <?php if($status=='edit'): ?> value="<?php echo e($trx->tanggal_akhir); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_sertifikat">Tanggal Sertifikat</label>
                            <input class="form-control" type="date" name="tanggal_sertifikat" id="tanggal_sertifikat" placeholder="Tanggal Sertifikat" <?php if($status=='edit'): ?> value="<?php echo e($trx->tanggal_sertifikat); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_kadaluarsa">Tanggal Kedaluwarsa</label>
                            <input class="form-control" type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" placeholder="Tanggal Kedaluwarsa" <?php if($status=='edit'): ?> value="<?php echo e($trx->tanggal_kadaluarsa); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="formulir_link">Link Formulir</label>
                            <input name="formulir_link" id="formulir_link" class="form-control" type="text" <?php if($status=='edit'): ?> value="<?php echo e($trx->formulir_link); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="sertifikat_link">Link Sertifikat</label>
                            <input name="sertifikat_link" id="sertifikat_link" class="form-control" type="text" required="" <?php if($status=='edit'): ?> value="<?php echo e($trx->sertifikat_link); ?>" <?php endif; ?>>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('pelatihan_sertifikat.index')); ?>" class="btn btn-warning" type="button">Back</a>
                        </div>
                    </div>
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
<!-- Moment.js (required for daterangepicker) -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<!-- Daterangepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
    // $('#tanggal-sertifikasi-container').hide().find('input, button').prop('disabled', true);
    // $(this).on('change', 'select[id="jenis[]"]', function () {
    //     const pilihan = $(this).val();
    //     if (pilihan.includes('5')) {
    //         $('#tanggal-sertifikasi-container').show().find('input, button').prop('disabled', false);
    //     } else {
    //         $('#tanggal-sertifikasi-container').hide().find('input, button').prop('disabled', true);
    //     }
    // });
    // 
    function initDateRangePicker(element) {
        element.daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' s/d '
            },
            autoUpdateInput: false
        });
        element.on('apply.daterangepicker', function(ev, picker) {
            const start = picker.startDate.format('YYYY-MM-DD');
            const end = picker.endDate.format('YYYY-MM-DD');

            // if (start === end) {
            //     $(this).val(start);
            // } else {
                $(this).val(start + ' s/d ' + end);
            // }
        });
        element.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }
    // Inisialisasi pertama tanggal pelaksanaan
    initDateRangePicker($('.tanggal-range'));
    // Tambah input baru
    $('#add-tanggal').click(function() {
        const newInput = $(`
            <div class="row col-md-12 pb-2 tanggal-group align-items-center">
                <div class="col-md-10">
                    <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly>
                    <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-tanggal"><i class="fa fa-minus" aria-hidden="true"></i></button>
                </div>
            </div>
        `);
        $('#tanggal-container').append(newInput);
        initDateRangePicker(newInput.find('.tanggal-range'));
    });
    // Tombol Hapus
    $('#tanggal-container').on('click', '.remove-tanggal', function () {
        $(this).closest('.tanggal-group').remove();
    });
    $('form').on('submit', function(e) {
        $(this).find('.tanggal-range').each(function() {
            if ($(this).val().trim() === '') {
                $(this).addClass('is-invalid').removeClass('is-valid');
                e.preventDefault();
            }else{
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/upsert_sertifikat.blade.php ENDPATH**/ ?>