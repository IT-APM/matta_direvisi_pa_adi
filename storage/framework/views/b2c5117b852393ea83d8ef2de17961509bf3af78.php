<?php $__env->startSection('title', 'Plafon Kesehatan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Plafon Kesehatan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <?php echo session('error'); ?>

                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('plafon.update', $plafon->id)); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('plafon.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="nik_karyawan">Karyawan</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="" <?php if($status=='edit' ): ?> disabled="true" <?php endif; ?>>
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $plafon->nik_karyawan): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="periode">Periode</label>
                                    <input type="number" min="0" name="periode" id="periode" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->periode); ?>" disabled="true" <?php endif; ?> >
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="kesehatan">Plafon kesehatan</label>
                                    <input type="number" min="0" name="kesehatan" id="kesehatan" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->kesehatan); ?>" <?php endif; ?> oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="lensa">Plafon lensa</label>
                                    <input type="number" min="0" name="lensa" id="lensa" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->lensa); ?>" <?php endif; ?> oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="frame">Plafon frame</label>
                                    <input type="number" min="0" name="frame" id="frame" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->frame); ?>" <?php endif; ?> oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="kehamilan">Plafon kehamilan</label>
                                    <input type="number" min="0" name="kehamilan" id="kehamilan" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->kehamilan); ?>" <?php endif; ?> oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="persalinan_normal">Plafon persalinan normal</label>
                                    <input type="number" min="0" name="persalinan_normal" id="persalinan_normal" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->persalinan_normal); ?>" <?php endif; ?> oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="persalinan_caesar">Plafon persalinan caesar</label>
                                    <input type="number" min="0" name="persalinan_caesar" id="persalinan_caesar" class="form-control numbers" required="" <?php if($status=='edit' ): ?> value="<?php echo e($plafon->persalinan_caesar); ?>" <?php endif; ?> oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('plafon.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/reimburse/upsert.blade.php ENDPATH**/ ?>