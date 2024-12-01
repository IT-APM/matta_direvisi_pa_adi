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
                    <h5>Formulir</h5>
                    <form action="<?php echo e(route('utilitas.update', 0)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=0; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=4; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=5; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=6; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=11; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=12; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=13; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=14; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=15; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=16; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=19; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=22; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=17; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=18; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=20; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=21; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=22; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=23; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=24; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=25; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=26; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=27; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=28; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=30; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="<?php echo e(route('utilitas.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    
                    <h5 class="mt-3">Surat Teguran dan Peringatan</h5>
                    <form action="<?php echo e(route('utilitas.update_sp', 0)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    <?php echo method_field('POST'); ?>
                    <?php echo csrf_field(); ?>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=0; ?>
                                <label class="form-label" for="sp_<?php echo e($no+1); ?>"><?php echo e($reminder[$no]->judul); ?></label>
                                <input name="sp_<?php echo e($no+1); ?>" id="sp_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($reminder[$no]->isi); ?>">
                            </div>
                            <div class="col-md-3">
                                <?php $no=2; ?>
                                <label class="form-label" for="sp_<?php echo e($no+1); ?>"><?php echo e($reminder[$no]->judul); ?></label>
                                <input name="sp_<?php echo e($no+1); ?>" id="sp_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($reminder[$no]->isi); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <?php $no=1; ?>
                                <label class="form-label" for="sp_<?php echo e($no+1); ?>"><?php echo e($reminder[$no]->judul); ?></label>
                                <textarea name="sp_<?php echo e($no+1); ?>" id="sp_<?php echo e($no+1); ?>" class="form-control" rows="4" required=""><?php echo e($reminder[$no]->isi); ?></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="<?php echo e(route('utilitas.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                    <h5 class="mt-3">Payroll</h5>
                    <form action="<?php echo e(route('utilitas.update_payroll', 0)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    <?php echo method_field('POST'); ?>
                    <?php echo csrf_field(); ?>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <?php $no=31; ?>
                                <label class="form-label" for="_<?php echo e($no+1); ?>"><?php echo e($utilitas[$no]->keterangan); ?></label>
                                <input name="_<?php echo e($no+1); ?>" id="_<?php echo e($no+1); ?>" class="form-control" type="text" required="" value="<?php echo e($utilitas[$no]->informasi); ?>">
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