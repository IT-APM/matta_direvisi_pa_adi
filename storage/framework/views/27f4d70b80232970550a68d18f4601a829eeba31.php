<?php $__env->startSection('title', 'Utilitas'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Edit</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Utilitas</li>
<li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <h5>Utilitas</h5>
                    <form action="<?php echo e(route('utilitas.update', 0)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php $no=1; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6">
                                <?php $no=5; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=6; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=7; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=12; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=13; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=14; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=15; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=16; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=17; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=20; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=23; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=18; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=19; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=21; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=22; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=42; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=24; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=46; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=25; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=26; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=27; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=28; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=38; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=31; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=33; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=29; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=37; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=39; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=40; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=41; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=43; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=44; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=47; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <textarea name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" rows="5" required=""><?php echo e($utilitas[$no-1]->informasi); ?></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="<?php echo e(route('utilitas.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h5>Surat Teguran dan Peringatan</h5>
                    <form action="<?php echo e(route('utilitas.update_sp', 0)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    <?php echo method_field('POST'); ?>
                    <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php $no=1; ?>
                                <label class="form-label" for="sp_<?php echo e($no); ?>"><?php echo e($reminder[$no-1]->judul); ?></label>
                                <input name="sp_<?php echo e($no); ?>" id="sp_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($reminder[$no-1]->isi); ?>">
                            </div>
                            <div class="col-md-6">
                                <?php $no=3; ?>
                                <label class="form-label" for="sp_<?php echo e($no); ?>"><?php echo e($reminder[$no-1]->judul); ?></label>
                                <input name="sp_<?php echo e($no); ?>" id="sp_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($reminder[$no-1]->isi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=2; ?>
                                <label class="form-label" for="sp_<?php echo e($no); ?>"><?php echo e($reminder[$no-1]->judul); ?></label>
                                <textarea name="sp_<?php echo e($no); ?>" id="sp_<?php echo e($no); ?>" class="form-control" rows="4" required=""><?php echo e($reminder[$no-1]->isi); ?></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="<?php echo e(route('utilitas.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                    <hr>
                    <h5>Payroll</h5>
                    <form action="<?php echo e(route('utilitas.update_payroll', 0)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    <?php echo method_field('POST'); ?>
                    <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php $no=32; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6">
                                <?php $no=36; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                            <div class="col-md-6 mt-2">
                                <?php $no=45; ?>
                                <label class="form-label" for="_<?php echo e($no); ?>"><?php echo e($utilitas[$no-1]->keterangan); ?></label>
                                <input name="_<?php echo e($no); ?>" id="_<?php echo e($no); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no-1]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="<?php echo e(route('utilitas.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/utilitas/upsert.blade.php ENDPATH**/ ?>