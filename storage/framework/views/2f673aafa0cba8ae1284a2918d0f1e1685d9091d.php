<?php $__env->startSection('title', 'Recruitment Edit'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Edit</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>
<?php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body typography">
                            <h5>Email</h5>
                            <form action="<?php echo e(route('recruitment.update', Crypt::encrypt($trx->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo method_field('PATCH'); ?>
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e($back_url); ?>">
                            <input type="hidden" id="type" name="type" value="<?php echo e($type); ?>">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%"><?php echo e($trx->nama); ?></td>
                                    </tr>
                                    <tr>
                                        <td><label for="email" class="form-label">Email Aktif<span class="text-danger">*</span></label></td>
                                        <td><input type="email" class="form-control" name="email" id="email" placeholder="email@email.com" required="" value="<?php echo e($trx->email); ?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e($back_url); ?>" class="btn btn-warning" type="button">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/edit_page.blade.php ENDPATH**/ ?>