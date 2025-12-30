<?php $__env->startSection('title', 'Payroll'); ?>

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
<h3>Payroll Master</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Master</li>
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
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link <?php if($id == 0): ?> active <?php endif; ?>" id="pills-karyawan-tab" data-bs-toggle="pill" href="#pills-karyawan" role="tab" aria-controls="pills-karyawan" aria-selected="false">Karyawan</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($id == 1): ?> active <?php endif; ?>" id="pills-tambah-tab" data-bs-toggle="pill" href="#pills-tambah" role="tab" aria-controls="pills-tambah" aria-selected="true">Penambahan</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($id == 2): ?> active <?php endif; ?>" id="pills-kurang-tab" data-bs-toggle="pill" href="#pills-kurang" role="tab" aria-controls="pills-kurang" aria-selected="true">Pengurangan</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($id == 3): ?> active <?php endif; ?>" id="pills-bpjstk-tab" data-bs-toggle="pill" href="#pills-bpjstk" role="tab" aria-controls="pills-bpjstk" aria-selected="true">BPJS TK</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($id == 4): ?> active <?php endif; ?>" id="pills-bpjskes-tab" data-bs-toggle="pill" href="#pills-bpjskes" role="tab" aria-controls="pills-bpjskes" aria-selected="true">BPJS Kesehatan</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($id == 5): ?> active <?php endif; ?>" id="pills-bpjsmax-tab" data-bs-toggle="pill" href="#pills-bpjsmax" role="tab" aria-controls="pills-bpjsmax" aria-selected="true">Parameter</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        
                        <div class="tab-pane fade <?php if($id == 0): ?> show active <?php endif; ?>" id="pills-karyawan" role="tabpanel" aria-labelledby="pills-karyawan-tab">
                            <p class="mb-0 mt-2">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <a href="<?php echo e(route('payroll.master',[0, 'tipe'=>'karyawan'])); ?>" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                    </div>
                                    <div class="col-md-10">
                                        <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" id="jenis" name="jenis" value="mtr">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <input name="template" id="template" class="form-control" type="file" required="">
                                                <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                
                                                <th>Pasangan</th>
                                                <th>Anak</th>
                                                <th>Tambahan</th>
                                                <th>No KTP</th>
                                                <th>No KPJ</th>
                                                <th>No JKN</th>
                                                <th>Rekening Atas Nama</th>
                                                <th>No. Rekening</th>
                                                <th>Nama Bank</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($data->_nik->nama); ?></td>
                                                <td><?php echo e($data->nik); ?></td>
                                                
                                                <td><?php echo e($data->pasangan); ?></td>
                                                <td><?php echo e($data->anak); ?></td>
                                                <td><?php echo e($data->tambahan); ?></td>
                                                <td><?php echo e($data->no_ktp); ?></td>
                                                <td><?php echo e($data->no_kpj); ?></td>
                                                <td><?php echo e($data->no_jkn); ?></td>
                                                <td><?php echo e($data->nama_rek); ?></td>
                                                <td><?php echo e($data->no_rek); ?></td>
                                                <td><?php echo e($data->nama_bank); ?></td>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[0, 'tipe'=>'del_karyawan', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[0, 'tipe'=>'edit_karyawan', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade <?php if($id == 1): ?> show active <?php endif; ?>" id="pills-tambah" role="tabpanel" aria-labelledby="pills-tambah-tab">
                            <p class="mb-0 m-t-10">
                                <a href="<?php echo e(route('payroll.master',[1, 'tipe'=>'add'])); ?>" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Bulanan</th>
                                                <th>Sumber</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($data->id); ?></td>
                                                <td><?php echo e($data->nama); ?></td>
                                                <td><?php if($data->bulanan == 1): ?> Iya <?php else: ?> Tidak <?php endif; ?></td>
                                                <td><?php echo e($data->status); ?></td>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[1, 'tipe'=>'del', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[1, 'tipe'=>'edit', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade <?php if($id == 2): ?> show active <?php endif; ?>" id="pills-kurang" role="tabpanel" aria-labelledby="pills-kurang-tab">
                            <p class="mb-0 m-t-10">
                                <a href="<?php echo e(route('payroll.master',[2, 'tipe'=>'sub'])); ?>" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Bulanan</th>
                                                <th>Sumber</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($data->id); ?></td>
                                                <td><?php echo e($data->nama); ?></td>
                                                <td><?php if($data->bulanan == 1): ?> Iya <?php else: ?> Tidak <?php endif; ?></td>
                                                <td><?php echo e($data->status); ?></td>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[2, 'tipe'=>'del', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[2, 'tipe'=>'edit', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade <?php if($id == 3): ?> show active <?php endif; ?>" id="pills-bpjstk" role="tabpanel" aria-labelledby="pills-bpjstk-tab">
                            <p class="mb-0 m-t-10">
                                <a href="<?php echo e(route('payroll.master',[3, 'tipe'=>'bpjstk'])); ?>" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Persen</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $bpjstk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($data->id); ?></td>
                                                <td><?php echo e($data->nama); ?></td>
                                                <td><?php echo e(number_format($data->persen, 2)); ?> %</td>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[3, 'tipe'=>'del_bpjstk', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[3, 'tipe'=>'edit_bpjstk', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade <?php if($id == 4): ?> show active <?php endif; ?>" id="pills-bpjskes" role="tabpanel" aria-labelledby="pills-bpjskes-tab">
                            <p class="mb-0 m-t-10">
                                <a href="<?php echo e(route('payroll.master',[4, 'tipe'=>'bpjsks'])); ?>" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Persen</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $bpjskes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($data->id); ?></td>
                                                <td><?php echo e($data->nama); ?></td>
                                                <td><?php echo e(number_format($data->persen, 2)); ?> %</td>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[4, 'tipe'=>'del_bpjsks', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[4, 'tipe'=>'edit_bpjsks', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade <?php if($id == 5): ?> show active <?php endif; ?>" id="pills-bpjsmax" role="tabpanel" aria-labelledby="pills-bpjsmax-tab">
                            <p class="mb-0 m-t-10">
                                <a href="<?php echo e(route('payroll.master',[5, 'tipe'=>'bpjsmx'])); ?>" class="btn btn-primary  mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable6">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Angka</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $bpjsmax; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($data->id); ?></td>
                                                <td><?php echo e($data->nama); ?></td>
                                                <td class="text-end"><?php echo e(number_format($data->angka, 0)); ?></td>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[5, 'tipe'=>'del_bpjsmx', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[5, 'tipe'=>'edit_bpjsmx', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
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
// 
// karyawan
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// penambahan
$('#_datatable2').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// pengurangan
$('#_datatable3').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// bpjstk
$('#_datatable4').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// bpjskes
$('#_datatable5').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// bpjsmax
$('#_datatable6').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/index_mtr.blade.php ENDPATH**/ ?>