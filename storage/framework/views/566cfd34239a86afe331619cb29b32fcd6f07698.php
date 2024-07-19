<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>To Do List</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Home</li>
<li class="breadcrumb-item active">To Do List</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!--  -->
    <div class="row widget-grid">
        <div class="col-xxl-6 col-sm-6 box-col-6">
            <div class="card profile-box">
                <div class="card-body bg-warning">
                    <div class="media">
                        <div class="media-body">
                            <div class="greeting-user">
                                <h6 class="f-w-600">Hi <?php echo e(auth()->user()->name); ?>,<br/>Welcome to MATTA Reborn</h6>
                                <p>Please check your account frequently!</p>
                                <!-- <div class="whatsnew-btn"><a class="btn btn-outline-white">Whats New !</a></div> -->
                                <div class="whatsnew-btn">&nbsp;</div>
                            </div>
                        </div>
                        <div>
                            <div class="clockbox">
                                <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                                    <g id="face">
                                        <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                                        <path class="hour-marks"
                                            d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6">
                                        </path>
                                        <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                                    </g>
                                    <g id="hour">
                                        <path class="hour-hand" d="M300.5 298V142"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="minute">
                                        <path class="minute-hand" d="M300.5 298V67"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="second">
                                        <path class="second-hand" d="M300.5 350V55"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"> </circle>
                                    </g>
                                </svg>
                            </div>
                            <div class="badge f-10 p-0" id="txt"></div>
                        </div>
                    </div>
                    <div class="cartoon">
                        <img class="img-fluid" src="<?php echo e(asset('assets/images/dashboard/cartoon.svg')); ?>" alt="i am">
                        <!-- <img class="img-fluid" src="<?php echo e(asset('assets/images/matta/karyawan/01031908799.jpg')); ?>" alt="i am"> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.lembur0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxOvertime)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-success" <?php endif; ?>><?php echo e(count($trxOvertime)); ?></span><br/><i class="fa fa-file-text-o"></i> OVERTIME</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.penilaian0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxPenilaian)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-primary" <?php endif; ?>><?php echo e(count($trxPenilaian)); ?></span><br/><i class="fa fa-file-text-o"></i> EVALUATION</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('asm')): ?>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.rekomendasi0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxRekomendasi)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-warning" <?php endif; ?>><?php echo e(count($trxRekomendasi)); ?></span><br/><i class="fa fa-file-text-o"></i> RECOMMENDATION</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.cuti0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxCuti)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-warning" <?php endif; ?>><?php echo e(count($trxCuti)); ?></span><br/><i class="fa fa-file-text-o"></i> LEAVE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.izin0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxIzin)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-success" <?php endif; ?>><?php echo e(count($trxIzin)); ?></span><br/><i class="fa fa-file-text-o"></i> PERMIT</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.nonaktif0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxNonAktif)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-primary" <?php endif; ?>><?php echo e(count($trxNonAktif)); ?></span><br/><i class="fa fa-file-text-o"></i> EXIT CLEARANCE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.reimburse0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxReimburse)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-primary" <?php endif; ?>><?php echo e(count($trxReimburse)); ?></span><br/><i class="fa fa-file-text-o"></i> REIMBURSEMENT</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('asm')): ?>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.kebutuhan0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxKebutuhan)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-warning" <?php endif; ?>><?php echo e(count($trxKebutuhan)); ?></span><br/><i class="fa fa-file-text-o"></i> ADD EMPLOYEES</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('spv')): ?>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.pelatihan0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxPelatihan)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-success" <?php endif; ?>><?php echo e(count($trxPelatihan)); ?></span><br/><i class="fa fa-file-text-o"></i> TRAINING</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bpd')): ?>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.bpd0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxBpd)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-success" <?php endif; ?>><?php echo e(count($trxBpd)); ?></span><br/><i class="fa fa-file-text-o"></i> TRAVEL EXPENSE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-xl-4 col-sm-4">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="<?php echo e(route('todolist.show', [0, 'filename' => 'emails.sakit0'])); ?>" style="color: #FFFFFF;">
                                <h5><span <?php if(count($trxSakit)>0): ?> class="badge bg-danger" <?php else: ?> class="badge bg-primary" <?php endif; ?>><?php echo e(count($trxSakit)); ?></span><br/><i class="fa fa-file-text-o"></i> SICK LEAVE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
</div>
<script type="text/javascript">
var session_layout = '<?php echo e(session()->get('layout ')); ?>';
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/clock.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead/handlebars.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead/typeahead.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead/typeahead.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead-search/handlebars.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/typeahead-search/typeahead-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/height-equal.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/animation/wow/wow.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/todolist/index.blade.php ENDPATH**/ ?>