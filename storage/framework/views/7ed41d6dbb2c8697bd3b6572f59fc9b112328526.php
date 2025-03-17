<?php $__env->startSection('title', 'Attendance'); ?>

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
<h3>Attendance Log</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Attendance</li>
<li class="breadcrumb-item active">Attendance Log</li>
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
                            <form class="mt-2" action="<?php echo e(route('attendance.index')); ?>" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Action</label>&nbsp;<input type="checkbox" onClick="toggle_att_type(this)" /> Check All
                                        <label class="d-block" for="att_type">
                                            <?php $__currentLoopData = $att_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="att_type[]" name="att_type[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_att_type)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                        <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_att_status(this)" /> Check All
                                        <label class="d-block" for="att_status">
                                            <?php $__currentLoopData = $att_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="att_status[]" name="att_status[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_att_status)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                        <label class="h6">Type</label>&nbsp;<input type="checkbox" onClick="toggle_att_data_src(this)" /> Check All
                                        <label class="d-block" for="att_data_src">
                                            <?php $__currentLoopData = $att_data_src; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="att_data_src[]" name="att_data_src[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_att_data_src)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Working Group</label>&nbsp;<input type="checkbox" onClick="toggle_wg_id(this)" /> Check All
                                        <label class="d-block" for="wg_id">
                                            <?php $__currentLoopData = $wg_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="wg_id[]" name="wg_id[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_wg_id)): ?> checked <?php endif; ?>> <?php echo e($data->wg_name); ?><br/>
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
                        <ul class="nav nav-tabs nav-primary mt-2" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="rawdata-tab" data-bs-toggle="pill" href="#rawdata" role="tab" aria-controls="rawdata" aria-selected="false">Raw Data</a></li>
                            <li class="nav-item"><a class="nav-link" id="duration-tab" data-bs-toggle="pill" href="#duration" role="tab" aria-controls="duration" aria-selected="true">Duration</a></li>
                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            
                            <div class="tab-pane fade show active" id="rawdata" role="tabpanel" aria-labelledby="rawdata-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display" id="raw_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Timestamp</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Perusahaan</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Working Group</th>
                                                <th>Action</th>
                                                <th>Longitude</th>
                                                <th>Latitude</th>
                                                <th>Address</th>
                                                <th>Map</th>
                                                <th>Status</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 0; ?>
                                        <tbody>
                                        <?php $__currentLoopData = $att; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data->att_date) ->format('d-m-Y')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data->att_timestamp) ->format('d-m-Y H:i:s')); ?></td>
                                                <td><?php echo e($data->_att_nik->nama); ?></td>
                                                <td><?php echo e($data->att_nik); ?></td>
                                                <td><?php echo e($data->_att_nik->_perusahaan->nama); ?></td>
                                                <td><?php echo e($data->_att_nik->_divisi->nama); ?></td>
                                                <td><?php echo e($data->_att_nik->_departemen->nama); ?></td>
                                                <td><?php echo e($data->_att_nik->_subdepartemen->nama); ?></td>
                                                <td><?php echo e($data->_att_nik->_wg->wg_name); ?></td>
                                                <td>
                                                    <?php if($data->att_type == 'Clock-in'): ?>
                                                        <span class="badge rounded-pill badge-light-primary"><?php echo e($data->att_type); ?></span>
                                                    <?php elseif($data->att_type == 'Clock-out'): ?>
                                                        <span class="badge rounded-pill badge-light-success"><?php echo e($data->att_type); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-pill badge-light-warning"><?php echo e($data->att_type); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($data->att_long); ?></td>
                                                <td><?php echo e($data->att_lat); ?></td>
                                                <td><?php echo e($data->att_address); ?></td>
                                                <td><?php if(!empty($data->att_long) && !empty($data->att_lat)): ?><a href="https://www.google.com/maps/place/<?php echo e($data->att_lat); ?>,<?php echo e($data->att_long); ?>" target="_blank"><i class="fa fa-map-marker" style="font-size:25px;color:red;"></i></a><?php endif; ?></td>
                                                <td>
                                                    <?php if($data->att_status == 'Book-in' || $data->att_status == 'Book-out'): ?>
                                                        <span class="badge badge-success"><?php echo e($data->att_status); ?></span>
                                                    <?php elseif($data->att_status == 'Leave'): ?>
                                                        <span class="badge badge-warning"><?php echo e($data->att_status); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger"><?php echo e($data->att_status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($data->att_data_src == 'Regular'): ?>
                                                        <span class="badge badge-success"><?php echo e($data->att_data_src); ?></span>
                                                    <?php elseif($data->att_data_src == 'Overtime'): ?>
                                                        <span class="badge badge-primary"><?php echo e($data->att_data_src); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning"><?php echo e($data->att_data_src); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="duration" role="tabpanel" aria-labelledby="duration-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display" id="duration_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Clock-in</th>
                                                <th>Clock-out</th>
                                                <th>Durasi</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Perusahaan</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Working Group</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 0; ?>
                                        <?php if(!empty($duration)): ?>
                                        <tbody>
                                        <?php $__currentLoopData = $duration; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data['att_date']) ->format('d-m-Y')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data['Clock-in']) ->format('d-m-Y H:i:s')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data['Clock-out']) ->format('d-m-Y H:i:s')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse(strtotime($data['Clock-out'])-strtotime($data['Clock-in']))->format('H:i:s')); ?></td>
                                                <?php
                                                $where = ['nik' => $data['att_nik']];
                                                $karyawan = App\Models\Karyawan::where($where)->first();
                                                ?>
                                                <td><?php echo e($karyawan->nama); ?></td>
                                                <td><?php echo e($data['att_nik']); ?></td>
                                                <td><?php echo e($karyawan->_perusahaan->nama); ?></td>
                                                <td><?php echo e($karyawan->_divisi->nama); ?></td>
                                                <td><?php echo e($karyawan->_departemen->nama); ?></td>
                                                <td><?php echo e($karyawan->_subdepartemen->nama); ?></td>
                                                <td><?php echo e($karyawan->_wg->wg_name); ?></td>
                                                <td>
                                                    <?php if($data['att_data_src'] == 'Regular'): ?>
                                                        <span class="badge badge-success"><?php echo e($data['att_data_src']); ?></span>
                                                    <?php elseif($data['att_data_src'] == 'Overtime'): ?>
                                                        <span class="badge badge-primary"><?php echo e($data['att_data_src']); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning"><?php echo e($data['att_data_src']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
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
function toggle_att_type(source) {
    checkboxes = document.getElementsByName('att_type[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_att_status(source) {
    checkboxes = document.getElementsByName('att_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_att_data_src(source) {
    checkboxes = document.getElementsByName('att_data_src[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_wg_id(source) {
    checkboxes = document.getElementsByName('wg_id[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#raw_datatable').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
$('#duration_datatable').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/attendance/index.blade.php ENDPATH**/ ?>