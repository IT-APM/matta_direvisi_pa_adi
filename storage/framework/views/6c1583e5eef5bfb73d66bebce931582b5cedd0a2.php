
<?php $__env->startSection('title', 'Formulir Penilaian Karyawan'); ?>

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
    <h3>Formulir Penilaian Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Formulir</li>
    <li class="breadcrumb-item active">Penilaian Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <?php if(Session::has('msg')): ?>
                            <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6><?php echo e(session('msg')); ?></h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                            <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-primary" type="button"><i
                                    class="fa fa-plus" aria-hidden="true"></i> Add new</a> |
                            <a href="<?php echo e(route('penilaian_auto.index')); ?>" class="btn btn-success" type="button"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Auto</a> |
                            <a href="<?php echo e(route('penilaian_reminder.index')); ?>" class="btn btn-info" type="button"><i
                                    class="fa fa-repeat" aria-hidden="true"></i> Reminder</a> |
                        <?php endif; ?>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i
                                class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="<?php echo e(route('penilaian.index')); ?>" method="get"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_id_status(this)" /> Check All
                                        <label class="d-block" for="id_status">
                                            <?php $__currentLoopData = $id_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="id_status[]" name="id_status[]"
                                                    type="checkbox" value="<?php echo e($data->id); ?>"
                                                    <?php if(in_array($data->id, $arr_id_status)): ?> checked <?php endif; ?>>
                                                <?php echo e($data->status_penilaian); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                            <label class="h6">Status Pengajuan Lengkap</label>
                                            <label class="d-block" for="id_status_lengkap">
                                                <?php $__currentLoopData = $id_status_lengkap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="id_status_lengkap[]"
                                                        name="id_status_lengkap[]" type="checkbox" value="<?php echo e($data); ?>"
                                                        <?php if(in_array($data, $arr_id_status_lengkap)): ?> checked <?php endif; ?>> Done By Reviewer 2 &
                                                    3<br />
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Status Perpanjangan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_is_perpanjang(this)" /> Check All
                                        <label class="d-block" for="is_perpanjang">
                                            <?php $__currentLoopData = $is_perpanjang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="is_perpanjang[]" name="is_perpanjang[]"
                                                    type="checkbox" value="<?php echo e($data->id); ?>"
                                                    <?php if(in_array($data->id, $arr_is_perpanjang)): ?> checked <?php endif; ?>>
                                                <?php echo e($data->informasi); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_perusahaan(this)" /> Check All
                                        <label class="d-block" for="perusahaan">
                                            <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]"
                                                    type="checkbox" value="<?php echo e($data->id); ?>"
                                                    <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>>
                                                <?php echo e($data->nama); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true"
                                            type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> |
                                        <button class="btn btn-info" id="reset" name="reset" value="true"
                                            type="submit"><i class="fa fa-refresh" aria-hidden="true"></i>
                                            Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br /><br />
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#ID</th>
                                        <th>Action</th>
                                        <th>Karyawan</th>
                                        <th>NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Selesai PKWT</th>
                                        <th>Penilai 1</th>
                                        <th>Penilai 2</th>
                                        <th>Penilai 3</th>
                                        <th>Penilaian ke</th>
                                        <th>Nilai Total Penilai 1</th>
                                        <th>Status Perpanjangan Penilai 1</th>
                                        <th>Status Perpanjangan Penilai 2</th>
                                        <th>Status Perpanjangan Penilai 3</th>
                                        <th>Created_at</th>
                                        <th>Timestamp Penilai 1</th>
                                        <th>Timestamp Penilai 2</th>
                                        <th>Timestamp Penilai 3</th>
                                        <th>Timestamp HCO</th>
                                        <th>Status</th>
                                        <th>Perpanjang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php
                                                $_karyawan_property = json_decode($data->karyawan_property, true);
                                            ?>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('penilaian.show', [$data->id, 'view' => 'detail'])); ?>"
                                                    data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                                &nbsp;<a
                                                    href="<?php echo e(route('penilaian.show', [$data->id, 'view' => 'pdf'])); ?>"
                                                    data-toggle="tooltip" title="PDF" target="_blank"><i
                                                        class="fa fa-file-pdf-o"></i></a>
                                                <br /><a
                                                    href="<?php echo e(route('penilaian.show', [$data->id, 'view' => 'result'])); ?>"
                                                    data-toggle="tooltip" title="Result" target="_blank"><i
                                                        class="fa fa-file-text-o"></i></a>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                                    &nbsp;<a href="<?php echo e(route('penilaian.edit', [$data->id])); ?>"
                                                        data-toggle="tooltip" title='Update'><i
                                                            class="icon-pencil-alt"></i></a>
                                                    
                                                    <br /><a href="<?php echo e(route('revisi_penilaian', [$data->id])); ?>"
                                                        data-toggle="tooltip" title='Revision'><i class="icon-pencil-alt"
                                                            style="color:red"></i></a>
                                                    
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($_karyawan_property['nama']); ?></td>
                                            <td><?php echo e($_karyawan_property['nik']); ?></td>
                                            <td><?php echo e($_karyawan_property['perusahaan']); ?></td>
                                            <td><?php echo e($data->_tipe->tipe_penilaian); ?></td>
                                            <td>
                                                <?php if(!empty($_karyawan_property['tanggal_berakhir'])): ?>
                                                    <?php echo e(Carbon\Carbon::parse($_karyawan_property['tanggal_berakhir'])->format('d-m-Y')); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->_penilai1->nama); ?></td>
                                            <td>
                                                <?php if(!empty($data->nik_penilai_2)): ?>
                                                    <?php echo e($data->_penilai2->nama); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($data->nik_penilai_3)): ?>
                                                    <?php echo e($data->_penilai3->nama); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- urutan -->
                                            <?php
                                                $urutan = App\Models\TrxPenilaian::where('nik_karyawan', $_karyawan_property['nik'])->where('id', '<=', $data->id)->count();
                                            ?>
                                            <td><?php echo e($urutan); ?></td>
                                            <!-- Nilai Total Penilai 1 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id];
                                                $skor_penilai_1 = App\Models\TrxPenilaianSkor::where($_where)->sum('skor_penilai_1');
                                            ?>
                                            <td><?php echo e($skor_penilai_1); ?></td>
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
                                            <td><?php echo e($data->created_at); ?></td>
                                            <!-- timestamp nik_penilai_1 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_1];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- timestamp nik_penilai_2 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_2];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- timestamp nik_penilai_3 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_3];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- timestamp hco -->
                                            <?php
                                                $_where = ['id_trx' => $data->id];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereIn('id_status', [4, 8])
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->_status->status_penilaian); ?></td>
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
        function toggle_id_status(source) {
            checkboxes = document.getElementsByName('id_status[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_is_perpanjang(source) {
            checkboxes = document.getElementsByName('is_perpanjang[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_perusahaan(source) {
            checkboxes = document.getElementsByName('perusahaan[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/index.blade.php ENDPATH**/ ?>