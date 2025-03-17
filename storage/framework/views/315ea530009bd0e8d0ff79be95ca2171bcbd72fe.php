<?php $__env->startSection('title', 'Overtime Forms'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> View <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Overtime</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> View <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="<?php echo e(asset('assets/images/other-images/sad.png')); ?>" alt="error"> -->
                        <h4>ERROR:</h4>
                        <?php if(session('error')['message'][0]['status']): ?> <h5>
                            <?php echo e(session('error')['message'][0]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')['message'][0]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')['message'][1]['status']): ?> <h5>
                            <?php echo e(session('error')['message'][1]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')['message'][1]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')['message'][2]['status']): ?> <h5>
                            <?php echo e(session('error')['message'][2]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')['message'][2]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')['message'][3]['status']): ?> <h5>
                            <?php echo e(session('error')['message'][3]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e(session('error')['message'][3]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if(session('error')['message'][4]['status']): ?> <h5>
                            <?php echo e(session('error')['message'][4]['message']); ?> - OK<br/>
                            <?php echo e(session('error')['message'][4]['quota']); ?></h5>
                        <?php else: ?> <h5><?php echo e(session('error')['message'][4]['message']); ?> - NOK<br/>
                        <?php echo e(session('error')['message'][4]['quota']); ?></h5> <?php endif; ?>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('overtime.update', $overtime->id)); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('overtime.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <?php if($status=='edit' ): ?> 
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_maker_by">Maker</label>
                                    <input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" required="" value="<?php echo e($overtime->ot_maker_by); ?>" >
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                                <?php else: ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_maker_by">Maker</label>
                                    <h6><?php echo e(auth()->user()->name); ?> / <?php echo e(auth()->user()->nik); ?></h6>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_start_at">Start</label>
                                    <input class="form-control digits" name="ot_start_at" id="ot_start_at" type="datetime-local" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_start_at); ?>" <?php endif; ?>>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_title">Title</label>
                                    <input name="ot_title" id="ot_title" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_title); ?>" <?php endif; ?>>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_end_at">Finish</label>
                                    <input class="form-control digits" name="ot_end_at" id="ot_end_at" type="datetime-local" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_end_at); ?>" <?php endif; ?>>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_desc">Description</label>
                                    <textarea class="form-control" id="ot_desc" name="ot_desc" rows="3" required=""><?php if($status == 'edit'): ?> <?php echo e($overtime->ot_desc); ?> <?php endif; ?></textarea>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_type_code">Type</label>
                                    <select class="form-select" id="ot_type_code" name="ot_type_code" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $type_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->ot_type); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $overtime->ot_type_code): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->ot_type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                            </div>
                            <br />
                            <?php if($status == 'create'): ?> <button class="btn btn-success" type="submit">Submit</button> | <?php endif; ?><a href="<?php echo e(route('overtime.index')); ?>" class="btn btn-warning" type="button">Back</a>
                        </form>
                </div>
            </div>
        </div>
        <!--  -->
        <?php if($status == 'edit'): ?>
        <div class="col-sm-4 notification box-col-4">
            <div class="card height-equal">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5 class="m-0">History</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <ul>
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="d-flex">
                            <?php if($loop->first): ?>
                            <div class="activity-dot-success"></div>
                            <?php elseif($loop->last): ?>
                            <div class="activity-dot-warning"></div>
                            <?php else: ?>
                            <div class="activity-dot-primary"></div>
                            <?php endif; ?>
                            <div class="ms-3">
                                <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                <h6><?php echo e($_history->ot_approver_status); ?><span class="dot-notification"></span></h6>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/overtime/propose/upsert.blade.php ENDPATH**/ ?>