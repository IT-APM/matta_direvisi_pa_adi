<?php $__env->startSection('title', 'Payroll Master'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if(in_array($tipe, ['edit_karyawan'])): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll Master</li>
<li class="breadcrumb-item">Karyawan</li>
<li class="breadcrumb-item active"><?php if(in_array($tipe, ['edit_karyawan'])): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form class="needs-validation" novalidate="" action="<?php echo e(route('payroll.upsert_master', ['id_data'=>$karyawan->id, 'tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php else: ?>
                    <form class="needs-validation" novalidate="" action="<?php echo e(route('payroll.upsert_master', ['tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <input type="hidden" id="_id" name="_id" value="<?php echo e($id); ?>">
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <?php $__currentLoopData = $_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if(in_array($tipe, ['edit_karyawan'])): ?> disabled="" <?php if($data->nik == $karyawan->nik): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">This is required.</div>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="pasangan">Pasangan</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="pasangan" id="pasangan" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->pasangan); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="anak">Anak</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="anak" id="anak" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->anak); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tambahan">Tambahan</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="tambahan" id="tambahan" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->tambahan); ?>" <?php endif; ?>>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="no_ktp">No KTP</label>
                                <input name="no_ktp" id="no_ktp" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->no_ktp); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="no_kpj">No KPJ</label>
                                <input name="no_kpj" id="no_kpj" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->no_kpj); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="no_jkn">No JKN</label>
                                <input name="no_jkn" id="no_jkn" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->no_jkn); ?>" <?php endif; ?>>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nama_rek">Rekening Atas Nama</label>
                                <input name="nama_rek" id="nama_rek" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->nama_rek); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="no_rek">No. Rekening</label>
                                <input name="no_rek" id="no_rek" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->no_rek); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="nama_bank">Nama Bank</label>
                                <input name="nama_bank" id="nama_bank" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_karyawan'])): ?> value="<?php echo e($karyawan->nama_bank); ?>" <?php endif; ?>>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('payroll.master', $id)); ?>" class="btn btn-warning" type="button">Cancel</a>
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
    function use_number(node) {
      var empty_val = false;
      const value = node.value;
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/upsert_karyawan.blade.php ENDPATH**/ ?>