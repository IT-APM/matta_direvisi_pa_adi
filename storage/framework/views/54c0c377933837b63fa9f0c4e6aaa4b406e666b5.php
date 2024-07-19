
<?php $__env->startSection('title', 'Biaya Perjalanan Dinas'); ?>

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
    <h3>Biaya Perjalanan Dinas</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Formulir</li>
    <li class="breadcrumb-item active">Biaya Perjalanan Dinas</li>
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
                                        <th>Nama / NIP</th>
                                        <th>Atasan / NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Level</th>
                                        <th>Jabatan</th>
                                        <th>Pengajuan</th>
                                        <th>Tahun</th>
                                        <th>Golongan</th>
                                        <th>Kelompok Perjalanan Dinas</th>
                                        <th>Jenis BPD</th>
                                        <th>Project ID</th>
                                        <th>Uraian Tugas Perjalanan Dinas</th>
                                        <th>Lampiran</th>
                                        <th>Informasi Perjalanan Dinas</th>
                                        
                                        
                                        <th>Kode COA</th>
                                        <th>Keterangan</th>
                                        <th>Qty</th>
                                        <th>Nominal</th>
                                        <th>Satuan</th>
                                        <th>Total</th>
                                        
                                        <th>Kode COA</th>
                                        <th>Keterangan</th>
                                        <th>Qty</th>
                                        <th>Nominal</th>
                                        <th>Satuan</th>
                                        <th>Total</th>
                                        
                                        <th>Admin / NIP</th>
                                        <th>Project Coordinator / NIP</th>
                                        <th>Layer 1 / NIP</th>
                                        <th>Layer 2 / NIP</th>
                                        <th>HCO / NIP</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $pengaju_property = json_decode($data['pengaju_property'], true);
                                            $admin_property = json_decode($data['admin_property'], true);
                                            $atasan_property = json_decode($data['atasan_property'], true);
                                            $koordinator_property = json_decode($data['koordinator_property'], true);
                                            $layer1_property = json_decode($data['layer1_property'], true);
                                            $layer2_property = json_decode($data['layer2_property'], true);
                                            $hco_property = json_decode($data['hco_property'], true);
                                        ?>
                                        <tr>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td><?php echo e($pengaju_property['nama']); ?> / <?php echo e($pengaju_property['nik']); ?></td>
                                            <td><?php echo e($atasan_property['nama']); ?> / <?php echo e($atasan_property['nik']); ?></td>
                                            <td><?php echo e($pengaju_property['perusahaan']); ?></td>
                                            <td><?php echo e($pengaju_property['divisi']); ?></td>
                                            <td><?php echo e($pengaju_property['dep']); ?></td>
                                            <td><?php echo e($pengaju_property['sub_dep']); ?></td>
                                            <td><?php echo e($pengaju_property['level']); ?></td>
                                            <td><?php echo e($pengaju_property['jabatan']); ?></td>
                                            <td>
                                                <?php if($data->is_ca == 1 && $data->luar_dalam == 'ca_dalam'): ?>
                                                    CA Dalam Negeri
                                                <?php elseif($data->is_ca == 1 && $data->luar_dalam == 'ca_luar'): ?>
                                                    CA Luar Negeri
                                                <?php elseif($data->is_ca == 0 && $data->luar_dalam == 'reimburse'): ?>
                                                    Reimbursement
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->periode); ?></td>
                                            <td>
                                                <?php if($data->is_leader == 1): ?>
                                                    Leader
                                                <?php elseif($data->is_leader == 2): ?>
                                                    Member
                                                <?php elseif($data->is_leader == 3): ?>
                                                    Management
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($data->kelompok_id)): ?>
                                                    <?php echo e($data->_kelompok->kelompok); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($data->is_project)): ?>
                                                    <?php if($data->is_project == 1): ?>
                                                        Project
                                                    <?php else: ?>
                                                        Corporate
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($data->is_project)): ?>
                                                    <?php echo e($data->_project->project_id); ?> / <?php echo e($data->_project->name); ?> /
                                                    <?php echo e($data->_project->group); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->uraian); ?></td>
                                            <td><a href="<?php echo e(asset('assets/bpd/' . $data->lampiran)); ?>" target="_blank"><i
                                                        class="icon-file"></i>&nbsp;</a></td>
                                            
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td>Jenis</td>
                                                        <td>Tujuan</td>
                                                        <td>Tanggal dari</td>
                                                        <td>Tanggal sampai</td>
                                                        <td>Jumlah hari</td>
                                                        <td>Uang saku</td>
                                                    </tr>
                                                  </table>
                                            </td>
                                            
                                            
                                            <td>Kode COA</td>
                                            <td>Keterangan</td>
                                            <td>Qty</td>
                                            <td>Nominal</td>
                                            <td>Satuan</td>
                                            <td>Total</td>
                                            
                                            <td>Kode COA</td>
                                            <td>Keterangan</td>
                                            <td>Qty</td>
                                            <td>Nominal</td>
                                            <td>Satuan</td>
                                            <td>Total</td>
                                            
                                            <td>
                                                <?php if(!empty($admin_property)): ?>
                                                    <?php echo e($admin_property['nama']); ?> / <?php echo e($admin_property['nik']); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($koordinator_property)): ?>
                                                    <?php echo e($koordinator_property['nama']); ?> /
                                                    <?php echo e($koordinator_property['nik']); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($layer1_property)): ?>
                                                    <?php echo e($layer1_property['nama']); ?> / <?php echo e($layer1_property['nik']); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($layer2_property)): ?>
                                                    <?php echo e($layer2_property['nama']); ?> / <?php echo e($layer2_property['nik']); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($hco_property)): ?>
                                                    <?php echo e($hco_property['nama']); ?> / <?php echo e($hco_property['nik']); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->_status->status); ?></td>
                                            <td><?php echo e($data->created_at); ?></td>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/bpd/raw_data.blade.php ENDPATH**/ ?>