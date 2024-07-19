<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pengaju</h5>
                        </div>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                    alt="user"></a>
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
                                        <td><?php echo e($pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep']); ?>

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
                        <!-- start -->
                        <div class="card-header">
                            <h5>Peserta</h5>
                        </div>
                        <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                    alt="user"></a>
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
                        <!-- end -->
                        <!-- start -->
                        <div class="card-header">
                            <h5>Atasan Peserta</h5>
                        </div>
                        <?php $__currentLoopData = $arrAtasanPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                    alt="user"></a>
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
                        <!-- end -->
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4 notification">
                    <div class="card">
                        <!-- start -->
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
                                        <p class="d-flex justify-content-between mb-2"><span
                                                class="date-content light-background"><?php echo e($_history->created_at); ?></span>
                                        </p>
                                        <h6><?php echo e($_history->_status->status_pengajuan); ?><span
                                                class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                            <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <!-- end -->
                        <!-- start -->
                        <div class="card-header">
                            <h5>Pelatihan</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Pelatihan ke</td>
                                        <td width="70%"><?php echo e($trx->ke); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td><?php echo e($trx->tahun); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <td>
                                            <?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($value->jenis); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Judul</td>
                                        <td><?php echo e($trx->judul); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Penyelenggara</td>
                                        <td><?php echo e($trx->penyelenggara); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Pelaksanaan</td>
                                        <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y')); ?>

                                            sampai
                                            <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tempat</td>
                                        <td><?php echo e($trx->tempat); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>Rp. <?php echo e(number_format($trx->biaya, 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Benefit</td>
                                        <td>
                                            <?php $__currentLoopData = $arrBenefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($value->benefit); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td><?php echo e($trx->_anggaran->anggaran); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td><?php echo $trx->keterangan; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end -->
                    </div>
                </div>
                <!--  -->
                <div class="col-12">
                    <div class="appointment">
                        <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/view.blade.php ENDPATH**/ ?>