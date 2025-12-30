<?php $__env->startSection('title', 'Daftar Sertifikat'); ?>

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
<h3>Daftar Sertifikat</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Pengajuan Pelatihan</li>
<li class="breadcrumb-item active">Daftar Sertifikat</li>
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
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jenis Pelatihan</th>
                                    <th>Penerbit</th>
                                    <th>Kartu Tanda Anggota</th>
                                    <th>Judul Pelatihan</th>
                                    <th>Lembaga</th>
                                    <th>Biaya</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>No Sertifikat</th>
                                    <th>Tanggal Sertifikat</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td>
                                        
                                        <?php if(!empty($data->id_trx)): ?>
                                        <a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php else: ?>
                                            
                                            <?php if(!empty($data->formulir_link)): ?>
                                            <a href="<?php echo e($data->formulir_link); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($data->nama); ?></td>
                                    <td><?php echo e($data->nik); ?></td>
                                    <td><?php echo e($data->jenis); ?></td>
                                    <td><?php echo e($data->penerbit); ?></td>
                                    <td><?php echo e($data->kta); ?></td>
                                    <td><?php echo e($data->judul); ?><?php if(!empty($data->int_ext)): ?><br/> (<?php echo e($data->int_ext); ?>) <?php endif; ?></td>
                                    <td><?php echo e($data->lembaga); ?></td>
                                    <td><?php echo e($data->biaya); ?></td>
                                    <td>
                                        <?php if(!empty($data->tanggal_mulai) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_mulai)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_mulai)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($data->tanggal_akhir) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_akhir)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_akhir)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($data->nomor_sertifikat); ?></td>
                                    <td>
                                        <?php if(!empty($data->tanggal_sertifikat) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_sertifikat)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_sertifikat)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($data->tanggal_kadaluarsa) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_kadaluarsa)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_kadaluarsa)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php if(!empty($data->sertifikat_link)): ?><a href="<?php echo e($data->sertifikat_link); ?>" data-toggle="tooltip" title="Sertifikat" target="_blank"><i class="fa fa-file-text-o"></i></a><?php endif; ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-warning mt-2" type="button">Back</a>
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
// function toggle_status_pengajuan(source) {
//     checkboxes = document.getElementsByName('status_pengajuan[]');
//     for(var i=0, n=checkboxes.length;i<n;i++) {
//         checkboxes[i].checked = source.checked;
//     }
// }
// function toggle_pengaju(source) {
//     checkboxes = document.getElementsByName('pengaju[]');
//     for(var i=0, n=checkboxes.length;i<n;i++) {
//         checkboxes[i].checked = source.checked;
//     }
// }
// function toggle_atasan_pengaju(source) {
//     checkboxes = document.getElementsByName('atasan_pengaju[]');
//     for(var i=0, n=checkboxes.length;i<n;i++) {
//         checkboxes[i].checked = source.checked;
//     }
// }
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/index_sertifikat.blade.php ENDPATH**/ ?>