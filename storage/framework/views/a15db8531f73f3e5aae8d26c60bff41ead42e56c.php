<?php $__env->startSection('title', 'Payroll Master'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if(in_array($tipe, ['edit_trxpayroll'])): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item">Transaction</li>
<li class="breadcrumb-item active"><?php if(in_array($tipe, ['edit_trxpayroll'])): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form class="needs-validation" novalidate="" action="<?php echo e(route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($trxpayroll->id), 'tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php else: ?>
                    <form class="needs-validation" novalidate="" action="<?php echo e(route('payroll.upsert_master', ['tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <input type="hidden" id="_id" name="_id" value="<?php echo e($id); ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <?php $__currentLoopData = $_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> disabled="" <?php if($data->nik == $trxpayroll->nik): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">This is required.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="bulan">Bulan</label>
                                <input min=1 type="number" name="bulan" id="bulan" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> disabled="" value="<?php echo e($trxpayroll->bulan); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="tahun">Tahun</label>
                                <input min=1 type="number" name="tahun" id="tahun" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> disabled="" value="<?php echo e($trxpayroll->tahun); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gapok">Gaji Pokok</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="gapok" id="gapok" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> value="<?php echo e($trxpayroll->gapok); ?>" <?php endif; ?>>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <h5>Penambahan</h5>
                                    <div class="col-md-6">
                                        <label class="form-label">Komponen</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Angka</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <h5>Pengurangan</h5>
                                    <div class="col-md-6">
                                        <label class="form-label">Komponen</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Angka</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <?php $__empty_1 = true; $__currentLoopData = $add_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_add_trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="add_id" name="add_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option disabled="" value="<?php echo e($data->id); ?>" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> <?php if($data->id == $_add_trx->id_addsub): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="add_angka_<?php echo e($_add_trx->id); ?>" id="add_angka_<?php echo e($_add_trx->id); ?>" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> value="<?php echo e($_add_trx->angka); ?>" <?php endif; ?>>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                
                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="add_id" name="add_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option disabled="" value="<?php echo e($data->id); ?>" <?php if($data->id == $data->id): ?> selected <?php endif; ?>><?php echo e($data->nama); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="add_angka_<?php echo e($data->id); ?>" id="add_angka_<?php echo e($data->id); ?>" class="form-control numbers" required="">
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?php $__empty_1 = true; $__currentLoopData = $sub_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_sub_trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="sub_id" name="sub_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <?php $__currentLoopData = $sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option disabled="" value="<?php echo e($data->id); ?>" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> <?php if($data->id == $_sub_trx->id_addsub): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="sub_angka_<?php echo e($_sub_trx->id); ?>" id="sub_angka_<?php echo e($_sub_trx->id); ?>" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_trxpayroll'])): ?> value="<?php echo e($_sub_trx->angka); ?>" <?php endif; ?>>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                
                                <?php $__currentLoopData = $sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="sub_id" name="sub_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option disabled="" value="<?php echo e($data->id); ?>" <?php if($data->id == $data->id): ?> selected <?php endif; ?>><?php echo e($data->nama); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="sub_angka_<?php echo e($data->id); ?>" id="sub_angka_<?php echo e($data->id); ?>" class="form-control numbers" required="">
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('payroll.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/upsert_payroll.blade.php ENDPATH**/ ?>