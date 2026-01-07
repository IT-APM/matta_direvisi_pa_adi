<?php $__env->startSection('title', 'Health Reporting'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Health Reporting</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Health</li>
<li class="breadcrumb-item active">Reporting</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="m-4" action="<?php echo e(route('health.index')); ?>" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br/><br/>
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama/NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Ring 1</th>
                                        <th>Ring 2</th>
                                        <th>Ring 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $karyawan_property = json_decode($data['trx_m']->karyawan_property, true);
                                ?>
                                    <tr>
                                       <td><?php echo e(++$no); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data['trx_m']->tanggal_input) ->format('d-m-Y')); ?></td>
                                       <td><?php if(!empty($karyawan_property)): ?><?php echo e($karyawan_property['nama']); ?> / <?php echo e($karyawan_property['nik']); ?><?php endif; ?></td>
                                       <td><?php if(!empty($karyawan_property)): ?><?php echo e($karyawan_property['perusahaan']); ?><?php endif; ?></td>
                                       <td><?php if(!empty($karyawan_property)): ?><?php echo e($karyawan_property['divisi']); ?><?php endif; ?></td>
                                       <td><?php if(!empty($karyawan_property)): ?><?php echo e($karyawan_property['dep']); ?><?php endif; ?></td>
                                       <td><?php if(!empty($karyawan_property)): ?><?php echo e($karyawan_property['sub_dep']); ?><?php endif; ?></td>
                                       <td>
                                            <?php $__currentLoopData = $data['trx_d']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($_data->ring_ke == 'ring1'): ?>
                                            Apakah anda dalam keadaan sehat hari ini? <?php echo e($_data->ring_isian); ?>

                                                <?php if(!empty($_data->siapa)): ?>
                                                <br/>Siapa: <?php echo e($_data->siapa); ?><br/>Sakit apa: <?php echo e($_data->sakit); ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </td>
                                       <td>
                                            <?php $__currentLoopData = $data['trx_d']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($_data->ring_ke == 'ring2'): ?>
                                            Orang serumah, Apakah ada yang sakit? <?php echo e($_data->ring_isian); ?>

                                                <?php if(!empty($_data->siapa)): ?>
                                                <br/>Siapa: <?php echo e($_data->siapa); ?><br/>Sakit apa: <?php echo e($_data->sakit); ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </td>
                                       <td>
                                            <?php $__currentLoopData = $data['trx_d']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($_data->ring_ke == 'ring3'): ?>
                                            Lingkungan tempat tinggal, Apakah ada yang sakit? <?php echo e($_data->ring_isian); ?>

                                                <?php if(!empty($_data->siapa)): ?>
                                                <br/>Siapa: <?php echo e($_data->siapa); ?><br/>Sakit apa: <?php echo e($_data->sakit); ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- State saving Ends-->
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
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/kesehatan/reporting/index.blade.php ENDPATH**/ ?>