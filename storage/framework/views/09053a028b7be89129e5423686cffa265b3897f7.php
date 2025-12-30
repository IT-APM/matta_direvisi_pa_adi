<?php $__env->startSection('title', 'Sertifikat'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Sertifikat</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
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
                    <?php $nik_property = json_decode($trx->nik_property, true); ?>
                    <div class="row">
                        <div class="col-md-4">
                            Peserta
                            <h6><?php echo e($nik_property['nama']); ?> / <?php echo e($nik_property['nik']); ?></h6>
                        </div>
                        <div class="col-md-4">
                            Jenis Pelatihan
                            <h6><?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($value->jenis); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></h6>
                        </div>
                        <div class="col-md-4">
                            Judul Pelatihan/Sertifikasi
                            <h6><?php echo e($trx->_trx->judul); ?></h6>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('sertifikat_detail'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id), 'back_url' => url()->full()])); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    
                                    
                                    <th>Nama Sertifikat</th>
                                    <th>No Sertifikat</th>
                                    <th>Penerbit</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Lulus</th>
                                    <th>Tanggal Berlaku</th>
                                    
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $nik_property = json_decode($data->nik_property, true); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    
                                    
                                    <td><?php echo e($data->nama_sertifikat); ?></td>
                                    <td><?php echo e($data->no_sertifikat); ?></td>
                                    <td><?php echo e($data->penerbit); ?></td>
                                    <?php
                                    $tanggal_ujian = [];
                                    $formatted_ranges = [];
                                    if(!empty($data->tanggal_ujian)){
                                        $tanggal_ujian = json_decode($data->tanggal_ujian, true);
                                        $formatted_ranges = array_map(function($range) {
                                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                        }, $tanggal_ujian);
                                    }
                                    ?>
                                    <td><?php if(!empty($formatted_ranges)): ?> <?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></td>
                                    <td><?php if($data->is_lulus === "1"): ?> Lulus <?php elseif($data->is_lulus === "0"): ?> Tidak Lulus <?php endif; ?></td>
                                    <?php
                                    $tanggal_berlaku = [];
                                    $formatted_ranges = [];
                                    if(!empty($data->tanggal_berlaku)){
                                        $tanggal_berlaku = json_decode($data->tanggal_berlaku, true);
                                        $formatted_ranges = array_map(function($range) {
                                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                        }, $tanggal_berlaku);
                                    }
                                    ?>
                                    <td><?php if(!empty($formatted_ranges)): ?> <?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></td>
                                    
                                    <td>
                                        <a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('sertifikat_update'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id), 'id_sertifikat' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'back_url' => url()->full()])); ?>" data-toggle="tooltip" title="Detail"><i class="fa fa-file-text-o"></i></a>
                                        &nbsp;<a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('penilaian'), 'id_penilaian' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'back_url' => url()->full()])); ?>" data-toggle="tooltip" title="Penilaian" target="_blank"><i class="icon-eye"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo e($back_url); ?>" class="btn btn-warning" type="button">Back</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/doc_index.blade.php ENDPATH**/ ?>