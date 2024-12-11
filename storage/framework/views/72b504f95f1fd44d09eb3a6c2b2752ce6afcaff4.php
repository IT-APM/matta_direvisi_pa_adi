
<?php $__env->startSection('title', 'Overtime Clock-in Clock-out'); ?>

<?php $__env->startSection('css'); ?>
<!--  -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Overtime Clock-in Clock-out</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Clock-in Clock-out</li>
<li class="breadcrumb-item active">Overtime</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <!-- <div class="card-body text-center my-auto"> -->
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($data)): ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($arr['ot_approver_status'] == 'Approve'): ?>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Nama/NIP</td>
                                    <td width="60%"><?php echo e($karyawan->nama); ?>/<?php echo e($karyawan->nik); ?></td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td><?php echo e($arr['ot_title']); ?></td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td><?php echo e($arr['ot_desc']); ?></td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td><?php echo e($arr['ot_type']); ?></td>
                                </tr>
                                <tr>
                                    <td>Start datetime</td>
                                    <td><?php echo e(date('d-m-Y H:i:s', strtotime($arr['ot_start_at']))); ?></td>
                                </tr>
                                <tr>
                                    <td>End datetime</td>
                                    <td><?php echo e(date('d-m-Y H:i:s', strtotime($arr['ot_end_at']))); ?></td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td><?php echo e(Carbon\CarbonInterval::seconds($arr['ot_length'])->cascade()->forHumans()); ?></td>
                                </tr>
                                <?php if($arr['n_clockin'] > 0): ?>
                                <tr>
                                    <td>Clock in</td>
                                    <td><?php echo e(date('d-m-Y H:i:s', strtotime($arr['dtClockin']))); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($arr['n_clockout'] > 0): ?>
                                <tr>
                                    <td>Clock out</td>
                                    <td><?php echo e(date('d-m-Y H:i:s', strtotime($arr['dtClockout']))); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <br />
                                <?php if($arr['isClockin']): ?>
                                <form action="<?php echo e(route('cico_ot.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-in?');">
                                    <?php echo csrf_field(); ?>
                                    <input name="id" id="id" class="form-control" type="hidden" value="<?php echo e($arr['id']); ?>">
                                    <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-in">
                                    <button class="btn btn-success" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i> Clock In</button>
                                </form>
                                <br />
                                <?php endif; ?>
                                <?php if($arr['isClockout']): ?>
                                <form action="<?php echo e(route('cico_ot.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-out?');">
                                    <?php echo csrf_field(); ?>
                                    <input name="id" id="id" class="form-control" type="hidden" value="<?php echo e($arr['id']); ?>">
                                    <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-out">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i> Clock Out</button>
                                </form>
                                <br />
                                <?php endif; ?>
                        <hr style="height:3px;border-width:0;color:orange" />
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <h4 class="text-center">No data available</h4>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!--  -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/cico/overtime.blade.php ENDPATH**/ ?>