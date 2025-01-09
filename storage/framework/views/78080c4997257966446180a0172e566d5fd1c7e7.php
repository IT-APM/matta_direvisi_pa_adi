<?php $__env->startSection('title', 'Payroll Master'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll Master</li>
<li class="breadcrumb-item"><?php if(in_array($tipe, ['bpjstk', 'edit_bpjstk'])): ?> BPJS TK <?php elseif(in_array($tipe, ['bpjsks', 'edit_bpjsks'])): ?> BPJS Kesehatan <?php elseif(in_array($tipe, ['bpjsmx', 'edit_bpjsmx'])): ?> BPJS Maksimal <?php endif; ?></li>
<li class="breadcrumb-item active"><?php if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($bpjs->id), 'tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php else: ?>
                    <form action="<?php echo e(route('payroll.upsert_master', ['tipe'=>$tipe])); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <input type="hidden" id="_id" name="_id" value="<?php echo e($id); ?>">
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nama">Komponen</label>
                                <input name="nama" id="nama" class="form-control" type="text" required="" <?php if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])): ?> value="<?php echo e($bpjs->nama); ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <?php if(in_array($tipe, ['bpjstk', 'bpjsks', 'edit_bpjstk', 'edit_bpjsks'])): ?>
                                <label class="form-label" for="persen">Persen (%)</label>
                                <input min=0 type="number" name="persen" id="persen" step="0.01" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks'])): ?> value="<?php echo e($bpjs->persen); ?>" <?php endif; ?>>
                                <?php elseif(in_array($tipe, ['bpjsmx', 'edit_bpjsmx'])): ?>
                                <label class="form-label" for="angka">Angka</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="angka" id="angka" class="form-control numbers" required="" <?php if(in_array($tipe, ['edit_bpjsmx'])): ?> value="<?php echo e($bpjs->angka); ?>" <?php endif; ?>>
                                <?php endif; ?>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/upsert_bpjs.blade.php ENDPATH**/ ?>