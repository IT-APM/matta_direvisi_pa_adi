
<?php $__env->startSection('title', 'Rekapitulasi Penilaian Karyawan'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        button.dt-button {
            float: none;
            text-align: center;
            color: black;
            background: white;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Rekapitulasi Penilaian Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Rekapitulasi</li>
    <li class="breadcrumb-item active">Penilaian Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#ID</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Penilaian ke</th>
                                        <th>Nilai Total</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Status Perpanjangan Penilai 1</th>
                                        <th>Status Perpanjangan Penilai 2</th>
                                        <th>Status Perpanjangan Penilai 3</th>
                                        <th>Status Perpanjangan HCO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php
                                                $karyawan = json_decode($data->karyawan_property, true);
                                            ?>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td><?php echo e($karyawan['nama']); ?></td>
                                            <td><?php echo e($karyawan['nik']); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td></td>
                                            <td><?php echo e($karyawan['tanggal_berakhir']); ?></td>
                                            <!-- nik_penilai_1 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_1];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereNotNull('is_perpanjang')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($trx)): ?>
                                                    <?php if($trx->is_perpanjang == 1): ?>
                                                        Diperpanjang <?php echo e($trx->bulan); ?> bulan
                                                    <?php else: ?>
                                                        Tidak Diperpanjang
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <!-- nik_penilai_2 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_2];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereNotNull('is_perpanjang')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($trx)): ?>
                                                    <?php if($trx->is_perpanjang == 1): ?>
                                                        Diperpanjang <?php echo e($trx->bulan); ?> bulan
                                                    <?php else: ?>
                                                        Tidak Diperpanjang
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <!-- nik_penilai_3 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_3];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereNotNull('is_perpanjang')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($trx)): ?>
                                                    <?php if($trx->is_perpanjang == 1): ?>
                                                        Diperpanjang <?php echo e($trx->bulan); ?> bulan
                                                    <?php else: ?>
                                                        Tidak Diperpanjang
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <!-- hco -->
                                            <td><?php echo e($data->_perpanjang->informasi); ?>

                                                <?php if($data->is_perpanjang == 1): ?>
                                                    <?php echo e($data->bulan); ?> Bulan
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
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
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
    <script type="text/javascript">
        // 
        $('#_datatable').DataTable({
            // dom: 'lBfrtip',
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'rt>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "lengthMenu": [
                [50, 100, 200, 500, -1],
                [50, 100, 200, 500, "All"]
            ]
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/rekapitulasi.blade.php ENDPATH**/ ?>