

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
    <li class="breadcrumb-item active">Infografis</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-current-tab" data-bs-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="true">Terkini</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-history-tab" data-bs-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="true">Histori</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                            <div class="row mt-2">
                                <?php $i = 0; ?>
                                <div class="col-xl-4 col-sm-4">
                                    
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?php echo e($lable[$i]); ?></h5>
                                        <div class="p-3">
                                            <img src="<?php echo e(asset('assets/images/matta/dashboard/people.png')); ?>" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2><?php echo e(array_sum(array_column($jumlah_karyawan_kelamin_now, 'jumlah'))); ?></h2>
                                    </div>
                                    
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?php echo e($lable[++$i]); ?></h5>
                                        <div class="p-3">
                                            <img src="<?php echo e(asset('assets/images/matta/dashboard/male-removebg-preview.png')); ?>" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2><?php echo e($jumlah_karyawan_kelamin_now['Pria']['jumlah']); ?></h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?php echo e($lable[++$i]); ?></h5>
                                        <div class="p-3">
                                            <img src="<?php echo e(asset('assets/images/matta/dashboard/female-removebg-preview.png')); ?>" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2><?php echo e($jumlah_karyawan_kelamin_now['Wanita']['jumlah']); ?></h2>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_kelamin_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_status_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_pendidikan_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_kelahiran_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_perusahaan_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_level_now"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                            <div class="row mt-2">
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_kelamin"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_perusahaan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_divisi"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_dep"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_subdep"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_status"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_level"></div>
                                </div>
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
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/chart-custom.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
    function generateColors(count){
        let colors = [];
        for(let i=0;i<count;i++){
            let hue = Math.floor((360 / count) * i);
            colors.push(`hsl(${hue},70%,50%)`);
        }
        return colors;
    }
    // 
    function render_chart_v1(dataObj, containerId, chartType, height) {
        const categories = Object.keys(dataObj);
        // const seriesKeys = Object.keys(dataObj[categories[0]]);
        // const series = seriesKeys.map(key => ({
        //     name: key,
        //     data: Object.values(dataObj).map(item => item[key])
        // }));
        const seriesKeys = [...new Set(Object.values(dataObj).flatMap(item => Object.keys(item)))].sort();
        const series = seriesKeys.map(key => ({
            name: key,
            data: Object.values(dataObj).map(item => item[key] || 0)
        }));
        const maxValue = Math.max(...series.flatMap(s => s.data)) + 5;
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
            },
            series: series,
            // colors: generateColors(data.length),
            // colors: [
            //     '#198754',
            //     '#dc3545',
            //     '#ffc107',
            // ],
            dataLabels: {
                // enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ['#000'],
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                tickPlacement: chartType === 'line' ? 'between' : 'on',
            },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v2(dataObj, containerId, chartType, height) {
        const categories = Object.keys(dataObj);
        const series = Object.values(dataObj).map(item => item.Aktif);
        const maxValue = Math.max(...series) + 5;
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
            },
            series: [{
                name: Object.keys(dataObj[categories[0]])[0],
                data: series
            }],
            // colors: [
            //     '#198754',
            //     '#dc3545',
            //     '#ffc107',
            // ],
            dataLabels: {
                // enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ['#000'],
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                tickPlacement: chartType === 'line' ? 'between' : 'on',
            },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v3(dataObj, containerId, chartType, height) {
        const labels = Object.keys(dataObj);
        const series = Object.values(dataObj).map(item => item.jumlah);
        const options = {
            chart: {
                type: chartType,
                height: height
            },
            series: series,
            labels: labels,
            // colors: ['#95a5a6', '#3498db', '#e74c3c'],
            legend: {
                position: 'top'
            },
            // dataLabels: {
            //     enabled: true,
            //     formatter: function (val, opts) {
            //         return opts.w.globals.labels[opts.seriesIndex] + ": " + val;
            //     }
            // }
        };
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v4(dataObj, containerId, chartType, height, colors) {
        const categories = Object.keys(dataObj);
        const series = Object.values(dataObj).map(item => item.jumlah);
        const maxValue = Math.max(...series) + 5;
        const options = {
            chart: {
                type: chartType,
                height: height
            },
            series: [{
                name: 'Jumlah',
                data: series
            }],
            // colors: [
            //     '#008FFB', biru
            //     '#00E396', hijau
            //     '#FEB019', orange
            //     '#FF4560', merah
            //     '#775DD0' ungu
            // ],
            colors: colors,
            xaxis: {
                categories: categories,
                labels: {
                    rotate: -45,
                    // offsetY: -5,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    trim: true,
                    // offsetY: 50,
                },
            },
            padding: {
                bottom: 500
            },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            dataLabels: {
                enabled: true
            },
        };
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // now
    render_chart_v3(<?php echo json_encode($jumlah_karyawan_kelamin_now, 15, 512) ?>, 'chart_jumlah_karyawan_kelamin_now', 'pie', 300);
    render_chart_v3(<?php echo json_encode($jumlah_karyawan_status_now, 15, 512) ?>, 'chart_jumlah_karyawan_status_now', 'pie', 300);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_perusahaan_now, 15, 512) ?>, 'chart_jumlah_karyawan_perusahaan_now', 'bar', 400, ['#008FFB']);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_level_now, 15, 512) ?>, 'chart_jumlah_karyawan_level_now', 'bar', 400, ['#775DD0']);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_pendidikan_now, 15, 512) ?>, 'chart_jumlah_karyawan_pendidikan_now', 'bar', 400, ['#00E396']);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_kelahiran_now, 15, 512) ?>, 'chart_jumlah_karyawan_kelahiran_now', 'bar', 400, ['#FEB019']);
    // history
    render_chart_v1(<?php echo json_encode($jumlah_karyawan, 15, 512) ?>, 'chart_jumlah_karyawan', 'line', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_kelamin, 15, 512) ?>, 'chart_jumlah_karyawan_kelamin', 'line', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_perusahaan, 15, 512) ?>, 'chart_jumlah_karyawan_perusahaan', 'line', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_divisi, 15, 512) ?>, 'chart_jumlah_karyawan_divisi', 'line', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_dep, 15, 512) ?>, 'chart_jumlah_karyawan_dep', 'line', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_subdep, 15, 512) ?>, 'chart_jumlah_karyawan_subdep', 'line', 700);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_status, 15, 512) ?>, 'chart_jumlah_karyawan_status', 'line', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_level, 15, 512) ?>, 'chart_jumlah_karyawan_level', 'line', 300);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/dashboard.blade.php ENDPATH**/ ?>