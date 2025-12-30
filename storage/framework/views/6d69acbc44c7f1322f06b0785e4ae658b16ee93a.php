
<?php $__env->startSection('title', 'Formulir Auto Penilaian Karyawan'); ?>

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
<h3>Birthday Email</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Birthday</li>
<li class="breadcrumb-item active">Email</li>
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
                    <ul class="nav nav-tabs nav-success" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-current-tab" data-bs-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="false">Current</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-history-tab" data-bs-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="true">History</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                            <form action="<?php echo e(route('birthday.send_email_exe')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="tipe" name="tipe" value="current">
                            <?php if(count($trx_current)>0): ?>
                            <button class="btn btn-success mt-2 mb-2" type="submit" name="submit" id="submit" value="execute" data-bs-toggle="tooltip" title="Kirimkan Email"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete" data-bs-toggle="tooltip" title="Delete Email"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="display" id="_datatable_current">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_current(this)" /></th>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Subject</th>
                                            
                                            <th>Created_at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no=0;
                                        ?>
                                        <?php $__currentLoopData = $trx_current; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $email = json_decode($data->email, true);
                                            ?>
                                            <td><input id="id_current[]" name="id_current[]" type="checkbox" value="<?php echo e($data->id); ?>"></td>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($nik_property['nama']); ?></td>
                                            <td><?php echo e($nik_property['nik']); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->birthday)->format('d-m-Y')); ?></td>
                                            <td><?php echo e($email['subject']); ?></td>
                                            
                                            <td><?php echo e($data->created_at); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                        </div>
                        
                        <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                            <div class="table-responsive">
                                <table class="display" id="_datatable_history">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Subject</th>
                                            
                                            <th>Created_at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no=0;
                                        ?>
                                        <?php $__currentLoopData = $trx_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $email = json_decode($data->email, true);
                                            ?>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($nik_property['nama']); ?></td>
                                            <td><?php echo e($nik_property['nik']); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->birthday)->format('d-m-Y')); ?></td>
                                            <td><?php echo e($email['subject']); ?></td>
                                            
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
// 
function toggle_status_current(source) {
    checkboxes = document.getElementsByName('id_current[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable_current').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
});
// 
$('#_datatable_history').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
});
// 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/birthday/index.blade.php ENDPATH**/ ?>