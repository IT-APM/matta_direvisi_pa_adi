<?php $__env->startSection('title', 'Payroll Adjustment'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Adjustment</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Adjustment</li>
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
                            <h5>Overtime</h5>
                            <form action="<?php echo e(route('payroll.save_edit_payroll')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e($trx->id); ?>">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e($back_url); ?>">
                            <input type="hidden" id="type" name="type" value="<?php echo e($type); ?>">
                            
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama/NIP</td>
                                        <td width="60%"><?php echo e($trx->_nik->nama); ?>/<?php echo e($trx->nik); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun/Bulan</td>
                                        <td><?php echo e($trx->tahun); ?>/<?php echo e($bulan[$trx->bulan - 1]); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Overtime Value</td>
                                        <td>Rp. <?php echo e(number_format($trx->overtime_ori)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Overtime Max Value</td>
                                        <td>Rp. <?php echo e(number_format($trx->overtime_max)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Overtime Payroll Value</td>
                                        <td><input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="overtime" id="overtime" class="form-control numbers" required="" value="<?php echo e($trx->overtime); ?>"></td>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/edit_payroll.blade.php ENDPATH**/ ?>