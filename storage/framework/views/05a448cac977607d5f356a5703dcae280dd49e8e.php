<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Update</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Pengajuan Pelatihan</li>
<li class="breadcrumb-item active">Update</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            
                    <div class="row">
                        <!-- start -->
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Pengaju</h5>
                                </div>
                                <div class="card-header">
                                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="30%">Nama</td>
                                                <td width="70%"><?php echo e($pengaju['nama']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td><?php echo e($pengaju['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($pengaju['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end -->
                        <!-- start -->
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Atasan Pengaju</h5>
                                </div>
                                <div class="card-header">
                                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan_pengaju['nik'].'/'.$atasan_pengaju['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan_pengaju['nik'].'/'.$atasan_pengaju['nik'].'.jpg')); ?>" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="30%">Nama</td>
                                                <td width="70%"><?php echo e($atasan_pengaju['nama']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td><?php echo e($atasan_pengaju['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($atasan_pengaju['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($atasan_pengaju['divisi'] . '/' . $atasan_pengaju['dep'] . '/' . $atasan_pengaju['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end -->
                        <!-- start -->
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Peserta</h5>
                                </div>
                                <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card-header">
                                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="30%">Nama</td>
                                                <td width="70%"><?php echo e($value['nama']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td><?php echo e($value['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($value['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <!-- end -->
                        <!-- start -->
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Atasan Peserta</h5>
                                </div>
                                <?php $__currentLoopData = $arrAtasanPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card-header">
                                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="30%">Nama</td>
                                                <td width="70%"><?php echo e($value['nama']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td><?php echo e($value['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($value['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <!-- end -->
                    </div>
                    <form action="<?php echo e(route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="status_pengajuan">Status Pengajuan</label>
                            <select class="form-select" id="status_pengajuan" name="status_pengajuan" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $status_pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trx->status_pengajuan): ?> selected <?php endif; ?> ><?php echo e($data->status_pengajuan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                        <button class="btn btn-success mt-2" type="submit">Update</button> | <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-warning" type="button">Back</a>
                    </form>
                
        </div>
        <div class="col-sm-3 notification">
            <!-- start -->
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body">
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
                                <h6><?php echo e($_history->_status->status_pengajuan); ?><span class="dot-notification"></span></h6>
                                <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
// 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/update.blade.php ENDPATH**/ ?>