
<?php $__env->startSection('title', 'Data Master Plafon Kesehatan'); ?>

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
<h3>Data Master Plafon Kesehatan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Plafon Kesehatan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
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
                    <?php if(Session::has('msg')): ?>
                    <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <a href="<?php echo e(route('plafon.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a> | 
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <form class="mt-2" action="<?php echo e(route('plafon.upload_data')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                    <?php echo csrf_field(); ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input name="template" id="template" class="form-control" type="file" required="">
                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                            <br/><a href="<?php echo e(asset('assets/template/Template Data Master Plafon Kesehatan.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                    </form>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('plafon.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label class="h6">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" /> Check All
                                    <label class="d-block" for="tahun">
                                        <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_tahun)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Tahun</th>
                                    <th>Kesehatan</th>
                                    <th>Sisa Plafon Kesehatan</th>
                                    <th>Lensa</th>
                                    <th>Sisa Plafon Lensa</th>
                                    <th>Frame</th>
                                    <th>Sisa Plafon Frame</th>
                                    <th>Kehamilan</th>
                                    <th>Sisa Plafon Kehamilan</th>
                                    <th>Persalinan Normal</th>
                                    <th>Sisa Plafon Persalinan Normal</th>
                                    <th>Persalinan Caesar</th>
                                    <th>Sisa Plafon Persalinan Caesar</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sisaPlafon = app('App\Http\Controllers\ReimbursePlafonController'); ?>
                                <?php $sisaPlafonNonKes = app('App\Http\Controllers\TrxReimburseController'); ?>
                                <?php $__currentLoopData = $plafon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->_nik->nama); ?></td>
                                    <td><?php echo e($data->nik_karyawan); ?></td>
                                    <td><?php echo e($data->periode); ?></td>
                                    <td><?php echo e(number_format($data->kesehatan)); ?></td>
                                    <td><?php echo e(number_format($data->kesehatan - $sisaPlafon::getSisaPlafon($data->nik_karyawan, $data->periode))); ?></td>
                                    <td><?php echo e(number_format($data->lensa)); ?></td>
                                    <!-- 3= Kacamata-Lensa -->
                                    <?php if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 3)['cek']): ?>
                                    <td><?php echo e(number_format(0)); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e(number_format($data->lensa)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(number_format($data->frame)); ?></td>
                                    <!-- 2= Kacamata-Frame -->
                                    <?php if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 2)['cek']): ?>
                                    <td><?php echo e(number_format(0)); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e(number_format($data->frame)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(number_format($data->kehamilan)); ?></td>
                                    <!-- 4= Kehamilan -->
                                    <?php if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 4)['cek']): ?>
                                    <td><?php echo e(number_format(0)); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e(number_format($data->kehamilan)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(number_format($data->persalinan_normal)); ?></td>
                                    <!-- 5= Persalinan-Normal -->
                                    <?php if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 5)['cek']): ?>
                                    <td><?php echo e(number_format(0)); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e(number_format($data->persalinan_normal)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(number_format($data->persalinan_caesar)); ?></td>
                                    <!-- 6= Persalinan-Caesar -->
                                    <?php if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 6)['cek']): ?>
                                    <td><?php echo e(number_format(0)); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e(number_format($data->persalinan_caesar)); ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <form action="<?php echo e(route('plafon.destroy', $data->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <!-- <a href="<?php echo e(route('plafon.edit', $data->id)); ?>"><i class="icon-pencil-alt"></i></a>&nbsp; -->
                                            <!-- <li class="delete"><a href="#" onclick="return confirm('Yakin mau dihapus?')"><i class="icon-trash"></i></a></li> -->
                                            <!-- <a onClick="return confirm('Are you sure want to delete this data?')"><i class="icon-trash"></i></a> -->
                                            <!-- <a href="#"><i class="icon-trash show_confirm"></i></a> -->
                                            <!-- <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button> -->
                                            <ul class="action">
                                                <li class="edit"><a href="<?php echo e(route('plafon.edit', $data->id)); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
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
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script type="text/javascript">
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
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
});
$(document).on('click', '#btn-delete', function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
$('.show_confirm').click(function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/reimburse/index.blade.php ENDPATH**/ ?>