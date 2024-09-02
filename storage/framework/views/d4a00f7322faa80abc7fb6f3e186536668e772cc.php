
<?php $__env->startSection('title', 'Basic DataTables'); ?>

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
<h3>Reminder & Warning Letter</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data</li>
<li class="breadcrumb-item active">Reminder & Warning Letter</li>
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
                    <form action="<?php echo e(route('reminder_sp.store')); ?>" method="post" enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure want to do this?');">
                        <?php echo csrf_field(); ?>
                        <?php if(count($trx) > 0): ?>
                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="execute"><i
                                class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button>
                        | <button class="btn btn-danger" type="submit" name="submit" id="submit" value="delete"><i
                                class="icon-trash" aria-hidden="true"></i> Delete</button>
                        <?php endif; ?>
                        <div class="mt-2 table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onClick="toggle_status_check(this)" /></th>
                                        <th>No</th>
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Working Group</th>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Level</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Email Karyawan</th>
                                        <th>Nama Atasan</th>
                                        <th>Email Atasan</th>
                                        <th>Jumlah Hari Kerja</th>
                                        <th>Jumlah Kehadiran</th>
                                        <th>Persentase Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $nik_property = json_decode($data->nik_property, true);
                                    $where = ['nik' => $nik_property['layer1']];
                                    $layer1 = App\Models\Karyawan::where($where)->first();
                                    ?>
                                    <tr>
                                        <td><input id="reminder_sp[]" name="reminder_sp[]" type="checkbox"
                                                value="<?php echo e($data->id); ?>"></td>
                                        <td><?php echo e(++$no); ?></td>
                                        <td><?php echo e($data->date_from); ?></td>
                                        <td><?php echo e($data->date_to); ?></td>
                                        <td><?php echo e($nik_property['wg_name']); ?></td>
                                        <td><?php echo e($data->nik); ?></td>
                                        <td><?php echo e($nik_property['nama']); ?></td>
                                        <td><?php echo e($nik_property['level']); ?></td>
                                        <td><?php echo e($nik_property['perusahaan']); ?></td>
                                        <td><?php echo e($nik_property['divisi']); ?></td>
                                        <td><?php echo e($nik_property['departemen']); ?></td>
                                        <td><?php echo e($nik_property['sub_departemen']); ?></td>
                                        <td><?php echo e($nik_property['email']); ?></td>
                                        <td><?php echo e($layer1->nama); ?></td>
                                        <td><?php echo e($layer1->email); ?></td>
                                        <td><?php echo e($data->arr_base); ?></td>
                                        <td><?php echo e($data->arr_clock); ?></td>
                                        <td><?php echo e(round($data->persentase)); ?> %</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
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
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script type="text/javascript">
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
    // 
    function toggle_status_check(source) {
        checkboxes = document.getElementsByName('reminder_sp[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/reminder_sp/index.blade.php ENDPATH**/ ?>