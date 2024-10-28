<?php $__env->startSection('title', 'Recruitment'); ?>

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
<h3>List Of Recruitment</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Recruitment</li>
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
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Pengaju</th>
                                    <th>Posisi</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Mulai Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0; ?>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $where = ['id_kebutuhan' => $data->id];
                                    $recruitment = App\Models\TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
                                ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('recruitment.show', [Crypt::encrypt(array($data->id, 1))])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="<?php echo e(route('kebutuhan.show', [$data->id, 'view' => 'pdf'])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php if(!empty($recruitment)): ?><br/><a href="<?php echo e(route('recruitment.apply', [Crypt::encrypt(array($recruitment->id, 'apply'))])); ?>" data-toggle="tooltip" title="Apply" target="_blank"><i class="icon-eye"></i></a><?php endif; ?>
                                    </td>
                                    <td><?php echo e($data->_pengaju->nama); ?> / <?php echo e($data->_pengaju->nik); ?></td>
                                    <td><?php echo e($data->_pekerjaan->pekerjaan); ?></td>
                                    <td><?php echo e($data->_status_kerja->status_kerja); ?></td>
                                    <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d-m-Y')); ?></td>
                                    <td>
                                        <?php if(!empty($recruitment)): ?>
                                        <a class="btn btn-success" href="<?php echo e(route('recruitment.show', [Crypt::encrypt(array($data->id, $recruitment->id_status))])); ?>" data-toggle="tooltip" title="<?php echo e($recruitment->_status->status); ?>" target="_blank"><?php echo e($recruitment->_status->status); ?></a>
                                        <?php else: ?>
                                        <a class="btn btn-info" href="<?php echo e(route('recruitment.show', [Crypt::encrypt(array($data->id, 1))])); ?>" data-toggle="tooltip" title="Process">Process</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/index.blade.php ENDPATH**/ ?>