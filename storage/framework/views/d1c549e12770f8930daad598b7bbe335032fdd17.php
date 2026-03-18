<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    /* button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    } */
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Dashboard</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Survey PORA</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(is_numeric($key)): ?>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card">
                                        <h5 class="text-center mt-2"><?php echo e($group['name']); ?></h5>
                                        <div id="chart-<?php echo e($key); ?>"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
$(document).ready(function() {
    const jsonData = <?php echo json_encode($result, 15, 512) ?>;
    const barColors = ["#FEB019", "#008FFB", "#00E396"];
    function renderBarChart(el, title, labels, data) {
      const options = {
        chart: {
          type: 'bar',
          height: 320
        },
        series: [{
          name: 'Jumlah',
          data: data
        }],
        xaxis: {
          categories: labels
        },
        yaxis: {
          min: 0,
          max: <?php echo e($y_max); ?>,
          tickAmount: 5
        },
        plotOptions: {
          bar: {
            distributed: true,
            borderRadius: 4
          }
        },
        colors: barColors,
        dataLabels: {
          enabled: true
        },
        legend: {
          show: false
        },
        // title: {
        //     text: title,
        //     align: 'center'
        // }
      };
      new ApexCharts(document.querySelector(el), options).render();
    }

    Object.keys(jsonData).forEach((key) => {
      if (!isNaN(key)) {
        const group = jsonData[key];
        const labels = group.opsi.map(item => item.name);
        const values = group.opsi.map(item => item.n);
        renderBarChart(
          `#chart-${key}`,
          group.name,
          labels,
          values
        );
      }
    });
});
</script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/chart-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/dashboard/survey_pora.blade.php ENDPATH**/ ?>