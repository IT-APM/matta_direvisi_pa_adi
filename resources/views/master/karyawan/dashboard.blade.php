@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    /* button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    } */
</style>
@endsection

@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Infografis</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link @if($id == 0) active @endif" id="pills-current-tab" data-bs-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="true">Terkini</a></li>
                        <li class="nav-item"><a class="nav-link @if($id == 1) active @endif" id="pills-history-tab" data-bs-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="true">Histori</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade  @if($id == 0) show active @endif" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                            <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                            <div class="collapse" id="collapse_filter">
                                <form id="form0" name="form0" class="mt-2" action="{{ route('karyawan.dashboard', 0) }}" method="get" enctype="multipart/form-data">
                                    <div class="row mt-2">
                                        <div class="col-sm-4">
                                            <label class="h6">Jenis Kelamin</label>&nbsp;<input type="checkbox" onClick="toggle_select_kelamin(this)" /> Check All
                                            <label class="d-block" for="select_kelamin">
                                                @foreach ($select_kelamin as $data)
                                                    <input class="checkbox_animated" id="select_kelamin[]" name="select_kelamin[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_kelamin)) checked @endif>{{ $data->kelamin }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_select_status(this)" /> Check All
                                            <label class="d-block" for="select_status">
                                                @foreach ($select_status as $data)
                                                    <input class="checkbox_animated" id="select_status[]" name="select_status[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_status)) checked @endif>{{ $data->status_karyawan }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_select_perusahaan(this)" /> Check All
                                            <label class="d-block" for="select_perusahaan">
                                                @foreach ($select_perusahaan as $data)
                                                    <input class="checkbox_animated" id="select_perusahaan[]" name="select_perusahaan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_perusahaan)) checked @endif>{{ $data->nama }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_select_level(this)" /> Check All
                                            <label class="d-block" for="select_level">
                                                @foreach ($select_level as $data)
                                                    <input class="checkbox_animated" id="select_level[]" name="select_level[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_level)) checked @endif>{{ $data->level }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Pendidikan</label>&nbsp;<input type="checkbox" onClick="toggle_select_pendidikan(this)" /> Check All
                                            <label class="d-block" for="select_pendidikan">
                                                @foreach ($select_pendidikan as $data)
                                                    <input class="checkbox_animated" id="select_pendidikan[]" name="select_pendidikan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_pendidikan)) checked @endif>{{ $data->jenjang }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Generasi</label>&nbsp;<input type="checkbox" onClick="toggle_select_generasi(this)" /> Check All
                                            <label class="d-block" for="select_generasi">
                                                @foreach ($select_generasi as $data)
                                                    <input class="checkbox_animated" id="select_generasi[]" name="select_generasi[]" type="checkbox" value="{{ $data['id'] }}" @if (in_array($data['id'], $arr_select_generasi)) checked @endif>{{ $data['data'] }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('karyawan.dashboard', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-2">
                                @php $i = 0; @endphp
                                <div class="col-xl-4 col-sm-4">
                                    {{-- <div class="card text-white bg-primary text-center"> --}}
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $lable[$i] }}</h5>
                                        <div class="p-3">
                                            <img src="{{ asset('assets/images/matta/dashboard/people.png') }}" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2>{{ array_sum(array_column($jumlah_karyawan_kelamin_now, 'jumlah')) }}</h2>
                                    </div>
                                    {{-- </div> --}}
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $lable[++$i] }}</h5>
                                        <div class="p-3">
                                            <img src="{{ asset('assets/images/matta/dashboard/male-removebg-preview.png') }}" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2>@if(!empty($jumlah_karyawan_kelamin_now['Pria']['jumlah'])){{ $jumlah_karyawan_kelamin_now['Pria']['jumlah'] }} @else 0 @endif</h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $lable[++$i] }}</h5>
                                        <div class="p-3">
                                            <img src="{{ asset('assets/images/matta/dashboard/female-removebg-preview.png') }}" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2>@if(!empty($jumlah_karyawan_kelamin_now['Wanita']['jumlah'])){{ $jumlah_karyawan_kelamin_now['Wanita']['jumlah'] }} @else 0 @endif</h2>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_kelamin_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_status_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_pendidikan_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_kelahiran_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_perusahaan_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_level_now"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if($id == 1) show active @endif" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                            <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                            <div class="collapse" id="collapse_filter">
                                <form id="form1" name="form1" class="mt-2" action="{{ route('karyawan.dashboard', 1) }}" method="get" enctype="multipart/form-data">
                                    <div class="row mt-2">
                                        <div class="col-sm-4">
                                            <label class="h6" for="nama">Bulan-Tahun</label>
                                            <div>
                                                <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_dari }}">
                                                <div class="invalid-feedback txt-danger">Field is required.</div>
                                            </div>
                                            sampai
                                            <div>
                                                <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_sampai }}">
                                                <div class="invalid-feedback txt-danger">Field is required.</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Jenis Kelamin</label>&nbsp;<input type="checkbox" onClick="toggle_select_kelamin(this)" /> Check All
                                            <label class="d-block" for="select_kelamin">
                                                @foreach ($select_kelamin as $data)
                                                    <input class="checkbox_animated" id="select_kelamin[]" name="select_kelamin[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_kelamin)) checked @endif>{{ $data->kelamin }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_select_status(this)" /> Check All
                                            <label class="d-block" for="select_status">
                                                @foreach ($select_status as $data)
                                                    <input class="checkbox_animated" id="select_status[]" name="select_status[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_status)) checked @endif>{{ $data->status_karyawan }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_select_perusahaan(this)" /> Check All
                                            <label class="d-block" for="select_perusahaan">
                                                @foreach ($select_perusahaan as $data)
                                                    <input class="checkbox_animated" id="select_perusahaan[]" name="select_perusahaan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_perusahaan)) checked @endif>{{ $data->nama }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_select_level(this)" /> Check All
                                            <label class="d-block" for="select_level">
                                                @foreach ($select_level as $data)
                                                    <input class="checkbox_animated" id="select_level[]" name="select_level[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_select_level)) checked @endif>{{ $data->level }}<br />
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('karyawan.dashboard', 1) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-2">
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_kelamin"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_status"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_level"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_perusahaan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_divisi"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_dep"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center">{{ $lable[++$i] }}</h5>
                                    <div id="chart_jumlah_karyawan_subdep"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
<script type="text/javascript">
// 
function toggle_select_kelamin(source) {
    checkboxes = document.getElementsByName('select_kelamin[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_status(source) {
    checkboxes = document.getElementsByName('select_status[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_perusahaan(source) {
    checkboxes = document.getElementsByName('select_perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_level(source) {
    checkboxes = document.getElementsByName('select_level[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_pendidikan(source) {
    checkboxes = document.getElementsByName('select_pendidikan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_generasi(source) {
    checkboxes = document.getElementsByName('select_generasi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_aktif(source) {
    checkboxes = document.getElementsByName('select_aktif[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$(document).ready(function() {
    $('#form1').on('submit', function (e) {
        let select_kelamin = $('input[name="select_kelamin[]"]:checked').length;
        if (select_kelamin < 1) {
            e.preventDefault();
            alert("Jenis Kelamin is mandatory");
        }
        let select_status = $('input[name="select_status[]"]:checked').length;
        if (select_status < 1) {
            e.preventDefault();
            alert("Status Karyawan is mandatory");
        }
        let select_perusahaan = $('input[name="select_perusahaan[]"]:checked').length;
        if (select_perusahaan < 1) {
            e.preventDefault();
            alert("Perusahaan is mandatory");
        }
        let select_level = $('input[name="select_level[]"]:checked').length;
        if (select_level < 1) {
            e.preventDefault();
            alert("Level is mandatory");
        }
    });
    $('#form0').on('submit', function (e) {
        let select_kelamin = $('input[name="select_kelamin[]"]:checked').length;
        if (select_kelamin < 1) {
            e.preventDefault();
            alert("Jenis Kelamin is mandatory");
        }
        let select_status = $('input[name="select_status[]"]:checked').length;
        if (select_status < 1) {
            e.preventDefault();
            alert("Status Karyawan is mandatory");
        }
        let select_perusahaan = $('input[name="select_perusahaan[]"]:checked').length;
        if (select_perusahaan < 1) {
            e.preventDefault();
            alert("Perusahaan is mandatory");
        }
        let select_level = $('input[name="select_level[]"]:checked').length;
        if (select_level < 1) {
            e.preventDefault();
            alert("Level is mandatory");
        }
        let select_generasi = $('input[name="select_generasi[]"]:checked').length;
        if (select_generasi < 1) {
            e.preventDefault();
            alert("Generasi is mandatory");
        }
    });
    // 
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
    render_chart_v3(@json($jumlah_karyawan_kelamin_now), 'chart_jumlah_karyawan_kelamin_now', 'pie', 300);
    render_chart_v3(@json($jumlah_karyawan_status_now), 'chart_jumlah_karyawan_status_now', 'pie', 300);
    render_chart_v4(@json($jumlah_karyawan_perusahaan_now), 'chart_jumlah_karyawan_perusahaan_now', 'bar', 400, ['#008FFB']);
    render_chart_v4(@json($jumlah_karyawan_level_now), 'chart_jumlah_karyawan_level_now', 'bar', 400, ['#775DD0']);
    render_chart_v4(@json($jumlah_karyawan_pendidikan_now), 'chart_jumlah_karyawan_pendidikan_now', 'bar', 400, ['#00E396']);
    render_chart_v4(@json($jumlah_karyawan_kelahiran_now), 'chart_jumlah_karyawan_kelahiran_now', 'bar', 400, ['#FEB019']);
    // history
    render_chart_v1(@json($jumlah_karyawan), 'chart_jumlah_karyawan', 'line', 300);
    render_chart_v1(@json($jumlah_karyawan_kelamin), 'chart_jumlah_karyawan_kelamin', 'line', 300);
    render_chart_v1(@json($jumlah_karyawan_perusahaan), 'chart_jumlah_karyawan_perusahaan', 'line', 300);
    render_chart_v1(@json($jumlah_karyawan_divisi), 'chart_jumlah_karyawan_divisi', 'line', 300);
    render_chart_v1(@json($jumlah_karyawan_dep), 'chart_jumlah_karyawan_dep', 'line', 300);
    render_chart_v1(@json($jumlah_karyawan_subdep), 'chart_jumlah_karyawan_subdep', 'line', 700);
    render_chart_v1(@json($jumlah_karyawan_status), 'chart_jumlah_karyawan_status', 'line', 300);
    render_chart_v1(@json($jumlah_karyawan_level), 'chart_jumlah_karyawan_level', 'line', 300);
});
</script>
@endsection
