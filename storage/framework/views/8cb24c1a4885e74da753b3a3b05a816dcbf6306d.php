<?php $__env->startSection('title', 'Jadwal'); ?>

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
<h3>Jadwal NOC 3 Group</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Jadwal</li>
<li class="breadcrumb-item active">NOC 3 Group</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <?php if(Session::has('msg')): ?>
                        <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                            <h6><?php echo e(session('msg')); ?></h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                            <h6>Upload File is ERROR</h6>
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        <div class="col-sm-6 mt-2">
                            <h6>Upload Jadwal</h6>
                            <form action="<?php echo e(route('working.upload_jadwal', 22)); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input name="template" id="template" class="form-control" type="file" required="">
                                    <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                    <br/><a href="<?php echo e(asset('assets/template/template_noc3_group_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download Template</a>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="<?php echo e(route('working.show', 22)); ?>" method="get" enctype="multipart/form-data">
                                <input type="hidden" id="req" name="req" value="view">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Tanggal</label>
                                        <br/>Dari <input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                        Sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                        <br/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Nama Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_nik_karyawan(this)" /> Check All
                                        <label class="d-block" for="nik_karyawan">
                                            <?php $__currentLoopData = $nik_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="nik_karyawan[]" name="nik_karyawan[]" type="checkbox" value="<?php echo e($data->nik); ?>" <?php if(in_array($data->nik, $arr_nik_karyawan)): ?> checked <?php endif; ?>> <?php echo e($data->nama); ?>/<?php echo e($data->nik); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('working.show', [22, 'req'=>'view'])); ?>" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama/NIP</th>
                                        <th>Tanggal</th>
                                        <th>Clock in</th>
                                        <th>Clock out</th>
                                        <th>Mulai Clock in</th>
                                        <th>Akhir Clock in</th>
                                        <th>Mulai Clock out</th>
                                        <th>Akhir Clock out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0; ?>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                       <td><?php echo e(++$no); ?></td>
                                       <td><?php echo e($data->_nik->nama); ?> / <?php echo e($data->nik); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->working_date)->format('d-m-Y')); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->clockin)->format('d-m-Y H:i:s')); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->clockout)->format('d-m-Y H:i:s')); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->start_clockin)->format('d-m-Y H:i:s')); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->end_clockin)->format('d-m-Y H:i:s')); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->start_clockout)->format('d-m-Y H:i:s')); ?></td>
                                       <td><?php echo e(\Carbon\Carbon::parse($data->end_clockout)->format('d-m-Y H:i:s')); ?></td>
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
function toggle_nik_karyawan(source) {
    checkboxes = document.getElementsByName('nik_karyawan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/jadwal/noc3/index.blade.php ENDPATH**/ ?>