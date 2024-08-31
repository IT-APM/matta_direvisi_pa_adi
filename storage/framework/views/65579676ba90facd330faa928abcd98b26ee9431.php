<?php $__env->startSection('title', 'Overtime Adjustment'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Adjustment</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Overtime</li>
<li class="breadcrumb-item active">Adjustment</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <?php
                $_data = json_decode($data->ot_maker_property, true);
                $_day = App\Http\Controllers\OvertimeController::isWeekDayWeekEndHoliday($data->ot_start_at, $data->ot_maker_by, $_data['is_reguler'], $_data['wg_id']);
                $_isHoliday = App\Http\Controllers\OvertimeController::isHoliday(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isHoliday'];
                $_isWeekend = App\Http\Controllers\OvertimeController::isWeekend(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isWeekend'];
                $_arr = json_decode($data->ot_freelance_property, true);
                ?>
                <!--  -->
                <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Company</td>
                                        <td width="60%"><?php echo e($_data['perusahaan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td><?php echo e($_data['divisi']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Department</td>
                                        <td><?php echo e($_data['dep']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Sub Department</td>
                                        <td><?php echo e($_data['sub_dep']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Maker by</td>
                                        <td width="60%"><?php echo e($data->_ot_maker_by->nama); ?> / <?php echo e($data->ot_maker_by); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Approve by</td>
                                        <td><?php echo e($data->_ot_approver_by->nama); ?> / <?php echo e($data->ot_approver_by); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Maker at</td>
                                        <td><?php echo e(\Carbon\Carbon::parse($data->ot_maker_at)->format('d-m-Y H:i:s')); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Approve at</td>
                                        <td><?php echo e(\Carbon\Carbon::parse($data->ot_approver_at)->format('d-m-Y H:i:s')); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Status</td>
                                        <td width="60%">
                                            <?php if($data->ot_approver_status == 'Approve'): ?>
                                            <span class="badge badge-success"><?php echo e($data->ot_approver_status); ?></span>
                                            <?php elseif($data->ot_approver_status == 'Waiting'): ?>
                                            <span class="badge badge-warning"><?php echo e($data->ot_approver_status); ?></span>
                                            <?php else: ?>
                                            <span class="badge badge-danger"><?php echo e($data->ot_approver_status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Title</td>
                                        <td><?php echo e($data->ot_title); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Desc</td>
                                        <td><?php echo e($data->ot_desc); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td><?php echo e($data->_ot_type_code->ot_type); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Start Plan</td>
                                        <td width="60%"><?php echo e(\Carbon\Carbon::parse($data->ot_start_at)->format('d-m-Y H:i:s')); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Finish Plan</td>
                                        <td><?php echo e(\Carbon\Carbon::parse($data->ot_end_at)->format('d-m-Y H:i:s')); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Length Plan</td>
                                        <!-- <td><?php echo e(\Carbon\CarbonInterval::seconds($data->ot_length)->cascade()->forHumans()); ?></td> -->
                                        <td><?php echo e(gmdate('H:i:s', $data->ot_length)); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Start Real</td>
                                        <td width="60%">
                                        <?php if(!empty($data->ot_start_at_real)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_start_at_real)->format('d-m-Y H:i:s')); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Finish Real</td>
                                        <td>
                                        <?php if(!empty($data->ot_end_at_real)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_end_at_real)->format('d-m-Y H:i:s')); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Length Real</td>
                                        <td>
                                        <?php if(!empty($data->ot_length_real)): ?> <?php echo e($data->ot_length_real); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Start Rev</td>
                                        <td width="60%">
                                        <?php if(!empty($data->ot_start_at_rev)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_start_at_rev)->format('d-m-Y H:i:s')); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Finish Rev</td>
                                        <td>
                                        <?php if(!empty($data->ot_end_at_rev)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_end_at_rev)->format('d-m-Y H:i:s')); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Length Rev</td>
                                        <td>
                                        <?php if(!empty($data->ot_length_rev)): ?> <?php echo e($data->ot_length_rev); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Basic Sallary</td>
                                        <!-- <td width="60%">Rp. <?php echo e(number_format($_data['gaji_pokok'])); ?></td> -->
                                        <td width="60%"><?php if($_data['status_karyawan']=='Freelance'): ?> <?php if($_isHoliday||$_isWeekend): ?> Rp. <?php echo e(number_format($_arr[1]['informasi'])); ?> <?php else: ?> Rp. <?php echo e(number_format($_arr[0]['informasi'])); ?> <?php endif; ?> <?php else: ?> Rp. <?php echo e(number_format($_data['gaji_pokok'])); ?> <?php endif; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Rp Rev</td>
                                        <td>Rp. <?php echo e(number_format($data->ot_length_rp)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Rp Rev (Round)</td>
                                        <td>Rp. <?php echo e(number_format(round($data->ot_length_rp, -3))); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <form action="<?php echo e(route('overtime.update', $data->id)); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo method_field('PATCH'); ?>
                            <?php echo csrf_field(); ?>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Length Adj</td>
                                        <td width="60%">
                                        <!-- <?php if(!empty($data->ot_length_adj)): ?> <?php echo e(\Carbon\CarbonInterval::seconds($data->ot_length_adj)->cascade()->forHumans()); ?> <?php endif; ?> -->
                                        <?php if(!empty($data->ot_length_adj)): ?> <?php echo e(gmdate('H:i:s', $data->ot_length_adj)); ?> <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rp Adj</td>
                                        <td>Rp. <?php echo e(number_format($data->ot_length_rp_adj)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Rp Adj (Round)</td>
                                        <td>Rp. <?php echo e(number_format(round($data->ot_length_rp_adj, -3))); ?></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Length Adj in seconds</td>
                                        <td><?php if(!empty($data->ot_length_adj)): ?> <?php echo e(number_format($data->ot_length_adj)); ?> <?php endif; ?>
                                    </tr> -->
                                    <tr>
                                        <td>Hours</td>
                                        <td><input type="number" min="0" max="23" name="hours" id="hours" class="form-control numbers" required="" value="<?php echo e($duration['hours']); ?>"></td>
                                    </tr>
                                    <tr>
                                        <td>Minutes</td>
                                        <td><input type="number" min="0" max="60" name="minutes" id="minutes" class="form-control numbers" required="" value="<?php echo e($duration['minutes']); ?>"></td>
                                    </tr>
                                    <tr>
                                        <td>Seconds</td>
                                        <td><input type="number" min="0" max="60" name="seconds" id="seconds" class="form-control numbers" required="" value="<?php echo e($duration['seconds']); ?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            <?php if(!empty($data->ot_length_adj)): ?> <button class="btn btn-success" type="submit">Submit</button> | <?php endif; ?> <a href="<?php echo e(route('reporting-index')); ?>" class="btn btn-warning" type="button">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Employee Status</td>
                                        <td width="60%"><?php echo e($_data['status_karyawan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Working Group</td>
                                        <td><?php echo e($_data['wg_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Day</td>
                                        <td><?php echo $_day; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <!-- <div class="col-12">
                    <div class="appointment">
                        <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/overtime/reporting/view.blade.php ENDPATH**/ ?>