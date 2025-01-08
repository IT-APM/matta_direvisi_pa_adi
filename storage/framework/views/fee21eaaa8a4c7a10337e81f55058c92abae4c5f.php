<?php $__env->startSection('title', 'Formulir Kebutuhan Karyawan'); ?>

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
<h3><?php echo e(__('lang.Formulir Kebutuhan Karyawan')); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item"><?php echo e(__('lang.Formulir')); ?></li>
<li class="breadcrumb-item active"><?php echo e(__('lang.Kebutuhan Karyawan')); ?></li>
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
                    <a href="<?php echo e(route('kebutuhan.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo e(__('lang.Tambah Baru')); ?></a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> <?php echo e(__('lang.Saring')); ?></button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('kebutuhan.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6"><?php echo e(__('lang.Status Pengajuan')); ?></label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> <?php echo e(__('lang.Pilih Semua')); ?>

                                    <label class="d-block" for="status_pengajuan">
                                        <?php $__currentLoopData = $status_pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_status_pengajuan)): ?> checked <?php endif; ?>> <?php echo e($data->status_pengajuan); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6"><?php echo e(__('lang.Area Kerja')); ?></label>&nbsp;<input type="checkbox" onClick="toggle_id_area(this)" /> <?php echo e(__('lang.Pilih Semua')); ?>

                                    <label class="d-block" for="id_area">
                                        <?php $__currentLoopData = $id_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="id_area[]" name="id_area[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_id_area)): ?> checked <?php endif; ?>> <?php echo e($data->propinsi); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6"><?php echo e(__('lang.Jenis Permintaan')); ?></label>&nbsp;<input type="checkbox" onClick="toggle_id_jenis_permintaan(this)" /> <?php echo e(__('lang.Pilih Semua')); ?>

                                    <label class="d-block" for="id_jenis_permintaan">
                                        <?php $__currentLoopData = $id_jenis_permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="id_jenis_permintaan[]" name="id_jenis_permintaan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_id_jenis_permintaan)): ?> checked <?php endif; ?>> <?php echo e($data->jenis); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> <?php echo e(__('lang.Saring')); ?></button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo e(__('lang.Atur Ulang')); ?></button>
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
                                    <th>#ID</th>
                                    <th><?php echo e(__('lang.Aksi')); ?></th>
                                    <th><?php echo e(__('lang.Area Kerja')); ?></th>
                                    <th><?php echo e(__('lang.Area Kerja Kota')); ?></th>
                                    <th><?php echo e(__('lang.Pengaju')); ?></th>
                                    <th><?php echo e(__('lang.NIP')); ?></th>
                                    <th><?php echo e(__('lang.Nama Jabatan')); ?></th>
                                    <th><?php echo e(__('lang.Status Kerja')); ?></th>
                                    <th><?php echo e(__('lang.Tanggal Mulai Bekerja')); ?></th>
                                    <th><?php echo e(__('lang.Jenis Permintaan')); ?></th>
                                    <th><?php echo e(__('lang.Jumlah (Orang)')); ?></th>
                                    <th><?php echo e(__('lang.Jenis Kelamin')); ?></th>
                                    <th><?php echo e(__('lang.Status Pengajuan')); ?></th>
                                    <th><?php echo e(__('lang.Waktu Pembuatan')); ?></th>
                                    <th><?php echo e(__('lang.Waktu Selesai')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="<?php echo e(route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                        <br /><a href="<?php echo e(route('kebutuhan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="<?php echo e(route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('doc')])); ?>" data-toggle="tooltip" title="Document"><i class="fa fa-file-text-o"></i></a>
                                        <?php endif; ?>

                                    </td>
                                    <td><?php echo e($data->_area->propinsi); ?></td>
                                    <td><?php if(!empty($data->id_kota)): ?> <?php echo e($data->_kota->kota); ?> <?php endif; ?></td>
                                    <td><?php echo e($data->_pengaju->nama); ?></td>
                                    <td><?php echo e($data->_pengaju->nik); ?></td>
                                    <td><?php echo e($data->_pekerjaan->pekerjaan); ?></td>
                                    <td><?php echo e($data->_status_kerja->status_kerja); ?></td>
                                    <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d-m-Y')); ?></td>
                                    <td><?php echo e($data->_jenis_permintaan->jenis); ?></td>
                                    <td><?php echo e($data->jumlah); ?></td>
                                    <td><?php echo e($data->_kelamin->kelamin); ?></td>
                                    <td><?php echo e($data->_status_pengajuan->status_pengajuan); ?></td>
                                    <td><?php echo e($data->created_at); ?></td>
                                    <td><?php if($data->status_pengajuan==10): ?> <?php echo e($data->updated_at); ?> <?php endif; ?></td>
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
function toggle_status_pengajuan(source) {
    checkboxes = document.getElementsByName('status_pengajuan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_area(source) {
    checkboxes = document.getElementsByName('id_area[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_jenis_permintaan(source) {
    checkboxes = document.getElementsByName('id_jenis_permintaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/index.blade.php ENDPATH**/ ?>