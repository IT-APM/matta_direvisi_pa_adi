<?php $__env->startSection('title', 'Error 400'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<div class="page-wrapper compact-wrapper" id="pageWrapper">
<!-- error-400 start-->
   <div class="error-wrapper">
        <div class="container"><img class="img-100" src="<?php echo e(asset('assets/images/other-images/sad.png')); ?>" alt="">
               <div class="error-heading">
                  <h2 class="headline font-danger">400</h2>
               </div>
               <div class="col-md-8 offset-md-2">
                  <p class="sub-content">Bad Request<br/>The request was invalid or cannot be otherwise served.</p>
               </div>
               
        </div>
   </div>
<!-- error-400 end-->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/errors/400.blade.php ENDPATH**/ ?>