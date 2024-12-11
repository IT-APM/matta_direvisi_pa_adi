<?php $__env->startSection('title', 'Formulir Permohonan Cuti'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Formulir Permohonan Cuti</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Cuti</li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <img class="img-100" src="<?php echo e(asset('assets/images/other-images/sad.png')); ?>" alt="">
                        <div class="error-heading">
                            <h2 class="headline font-danger">Error</h2>
                            <?php if($arr[0]['status']): ?> <h5 class="headline font-success"><?php echo e($arr[0]['message']); ?> - OK</h5>
                            <?php else: ?> <h5 class="headline font-danger"><?php echo e($arr[0]['message']); ?> - NOK</h5> <?php endif; ?>
                            <?php if($arr[1]['status']): ?> <h5 class="headline font-success"><?php echo e($arr[1]['message']); ?> - OK</h5>
                            <?php else: ?> <h5 class="headline font-danger"><?php echo e($arr[1]['message']); ?> - NOK</h5> <?php endif; ?>
                            <?php if($arr[2]['status']): ?> <h5 class="headline font-success"><?php echo e($arr[2]['message']); ?> - OK</h5>
                            <?php else: ?> <h5 class="headline font-danger"><?php echo e($arr[2]['message']); ?> - NOK</h5> <?php endif; ?>
                            <?php if($arr[3]['status']): ?> <h5 class="headline font-success"><?php echo e($arr[3]['message']); ?> - OK</h5>
                            <?php else: ?> <h5 class="headline font-danger"><?php echo e($arr[3]['message']); ?> - NOK</h5> <?php endif; ?>
                            <?php if($arr[4]['status']): ?> <h5 class="headline font-success"><?php echo e($arr[4]['message']); ?> - OK</h5>
                            <?php else: ?> <h5 class="headline font-danger"><?php echo e($arr[4]['message']); ?> - NOK</h5> <?php endif; ?>
                        </div>
                        <div class="col-md-12">
                            <!-- <p class="sub-content">Please contact your system administrator!</p> -->
                        </div>
                        <div><a class="btn btn-danger-gradien btn-lg" href="javascript:history.back()">BACK</a></div>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/error.blade.php ENDPATH**/ ?>