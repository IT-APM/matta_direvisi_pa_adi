<?php $__env->startSection('title', 'Error DB'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Error</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Error</li>
<li class="breadcrumb-item active">DB</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <!-- <div class="page-wrapper compact-wrapper" id="pageWrapper"> -->
          <!-- error-404 start-->
          <!-- <div class="error-wrapper"> -->
          <div class="container text-center">
            <img class="img-100" src="<?php echo e(asset('assets/images/other-images/sad.png')); ?>" alt="">
            <h1 class="headline font-danger">Error DB</h1>
            <!-- <div class="col-md-8 offset-md-2"> -->
            <h4 class="sub-content font-danger">Please contact your system administrator!</h4>
            <!-- </div> -->
            <div><a class="btn btn-danger-gradien btn-lg" href="<?php echo e(URL::previous()); ?>">BACK</a></div>
          </div>
          <!-- </div> -->
          <!-- error-404 end      -->
          <!-- </div> -->
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/errors/error_db.blade.php ENDPATH**/ ?>