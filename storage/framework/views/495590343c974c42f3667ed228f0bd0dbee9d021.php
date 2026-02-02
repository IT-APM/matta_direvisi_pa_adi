
<?php $__env->startSection('title', 'Data Master Pekerjaan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Detail</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Pekerjaan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="h6">Bidang Pekerjaan</label>
                            <?php $__currentLoopData = $bidang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="d-block" for="bidang">
                                <input class="checkbox_animated" id="bidang" name="bidang" type="checkbox" value="<?php echo e($data->id); ?>"> <?php echo e($data->bidang); ?>

                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('pekerjaan_bidang.index')); ?>" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                            <br/><button class="btn btn-warning" id="filter" name="filter" type="button"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                            <!-- <label class="form-label" for="bidang[]">Bidang</label>
                            <div class="col-sm-12 pb-2">
                                <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple"
                                    id="bidang[]" name="bidang[]">
                                    <?php $__currentLoopData = $bidang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->bidang); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div> -->
                        </div>
                        <div class="col-sm-9">
                            <?php if(Session::has('msg')): ?>
                            <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6><?php echo e(session('msg')); ?></h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php endif; ?>
                            <a href="<?php echo e(route('pekerjaan_master.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a>
                            <div class="table-responsive mt-2">
                                <!-- id="basic-1" -->
                                <table class="display" id="_table_">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bidang</th>
                                            <th>Pekerjaan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--  -->
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
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
var dataTable;
$(document).ready(function() {
    // 
    loadData([0]);
    $("#filter").click(function() {
        var bidang = [0];
        $.each($("input[name='bidang']:checked"), function() {
            bidang.push($(this).val());
        });
        loadData(bidang);
    });
    // 
    $("#reset").click(function() {
        $("input[name='bidang']").prop("checked", false);
        loadData([0]);
    });
    // 
    function loadData(bidang) {
        // alert(bidang);
        $('#_table_').DataTable().clear().destroy();
        var i = 1;
        dataTable = $('#_table_').DataTable({
            // destroy: true,
            // retrieve: true,
            processing: true,
            serverSide: false,
            ajax: {
                // url: 'http://localhost/datatable/',
                url: "<?php echo e(route('pekerjaan_master_pekerjaan')); ?>" + "/" + bidang.join(","),
                type: "GET",
                dataSrc: ""
            },
            columns: [{
                    "render": function() {
                        return i++;
                    }
                },
                {
                    "data": "id_bidang"
                },
                {
                    "data": "pekerjaan"
                },
                {
                    "data": "id",
                    "render": function(data) {
                        var urlView = '<?php echo e(route("pekerjaan_master.show", ":id")); ?>';
                        urlView = urlView.replace(':id', data);
                        var urlDel = '<?php echo e(route("pekerjaan_master.edit", ":id")); ?>';
                        urlDel = urlDel.replace(':id', data);
                        return `<a href="`+urlView+`" data-toggle="tooltip" title="View"><i class="icon-eye"></i></a>
                        &nbsp;<a href="`+urlDel+`" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>`;
                    }
                }
            ],
        });
    }
    // 
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/pekerjaan/index.blade.php ENDPATH**/ ?>