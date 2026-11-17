@extends('layouts.simple.master')
@section('title', 'Attendance')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
<style>
    .filter-box {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        padding: 12px;
        border-radius: 8px;
        background: #f8fafc;
    }
    .map-marker-icon {
        font-size: 18px !important;
        color: #ff5400;
        vertical-align: middle;
        transition: transform 0.2s ease;
    }
    .map-marker-icon:hover {
        transform: scale(1.2);
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Attendance Log</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Attendance</li>
<li class="breadcrumb-item active">Attendance Log</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('attendance.index') }}" method="get">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required value="{{ $date_from }}">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required value="{{ $date_to }}">
                                        <label class="h6 mt-2">Karyawan</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="str_att_nik_nama" name="str_att_nik_nama">
                                            <option selected disabled value="">Pilih...</option>
                                            <option value="">Semua</option>
                                            @foreach ($att_nik_nama as $data)
                                                <option value="{{ $data->att_nik }}" @if($data->att_nik == $str_att_nik_nama) selected @endif>{{ $data->nama }} - {{ $data->att_nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Action</label>&nbsp;<input type="checkbox" onClick="toggleCheck('att_type[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                @foreach ($att_type as $data)
                                                <input class="checkbox_animated" name="att_type[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_att_type)) checked @endif> {{ $data }}<br/>
                                                @endforeach
                                            </label>
                                        </div>
                                        <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggleCheck('att_status[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                @foreach ($att_status as $data)
                                                <input class="checkbox_animated" name="att_status[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_att_status)) checked @endif> {{ $data }}<br/>
                                                @endforeach
                                            </label>
                                        </div>
                                        <label class="h6">Type</label>&nbsp;<input type="checkbox" onClick="toggleCheck('att_data_src[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                @foreach ($att_data_src as $data)
                                                <input class="checkbox_animated" name="att_data_src[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_att_data_src)) checked @endif> {{ $data }}<br/>
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Working Group</label>&nbsp;<input type="checkbox" onClick="toggleCheck('wg_id[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                @foreach ($wg_id as $data)
                                                <input class="checkbox_animated" name="wg_id[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_wg_id)) checked @endif> {{ $data->wg_name }}<br/>
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Company</label>&nbsp;<input type="checkbox" onClick="toggleCheck('perusahaan[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                @foreach ($perusahaan as $data)
                                                <input class="checkbox_animated" name="perusahaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_perusahaan)) checked @endif> {{ $data->nama }}<br/>
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('attendance.index') }}" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <ul class="nav nav-tabs nav-primary mt-2" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="rawdata-tab" data-bs-toggle="pill" href="#rawdata" role="tab" aria-controls="rawdata" aria-selected="true">Raw Data</a></li>
                            <li class="nav-item"><a class="nav-link" id="duration-tab" data-bs-toggle="pill" href="#duration" role="tab" aria-controls="duration" aria-selected="false">Duration</a></li>
                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            {{-- rawdata --}}
                            <div class="tab-pane fade show active" id="rawdata" role="tabpanel" aria-labelledby="rawdata-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display table table-striped table-bordered" id="raw_datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Timestamp</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>ID_FP</th>
                                                <th>Perusahaan</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Lokasi</th>
                                                <th>Working Group</th>
                                                <th>Action</th>
                                                <th>Longitude</th>
                                                <th>Latitude</th>
                                                <th>Address</th>
                                                <th>Map</th>
                                                <th>Status</th>
                                                <th>Delay</th>
                                                <th>Type</th>
                                                <th>Source</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- duration --}}
                            <div class="tab-pane fade" id="duration" role="tabpanel" aria-labelledby="duration-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display table table-striped table-bordered" id="duration_datatable" style="width: 100%;">
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
                                                <th>Lokasi</th>
                                                <th>Working Group</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
<script type="text/javascript">
// Check All untuk grup checkbox
function toggleCheck(name, source) {
    document.getElementsByName(name).forEach(cb => cb.checked = source.checked);
}

const filterParams = {
    filter: '{{ $isFilter ? 1 : 0 }}',
    date_from: '{{ $date_from }}',
    date_to: '{{ $date_to }}',
    str_att_nik_nama: '{{ $str_att_nik_nama }}',
    att_type: @json($arr_att_type),
    att_status: @json($arr_att_status),
    att_data_src: @json($arr_att_data_src),
    perusahaan: @json($arr_perusahaan),
    wg_id: @json($arr_wg_id)
};

// DataTables Server-Side untuk Raw Data (membuka instan, paginasi 25, memuat 40.000 data lengkap)
const tblRaw = $('#raw_datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('attendance.index') }}",
        type: 'GET',
        data: function(d) {
            d.tab = 'raw';
            $.extend(d, filterParams);
        }
    },
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
         "<'row'<'col-sm-12'rt>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
    order: [[0, 'desc']],
    autoWidth: false,
    pageLength: 25,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    language: {
        processing: '<div class="spinner-border text-primary spinner-border-sm" role="status"></div> Memuat data...',
        search: "Search:",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous"
        }
    }
});

let tblDur = null;
$('#duration-tab').on('shown.bs.tab', function () {
    if (!tblDur) {
        tblDur = $('#duration_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('attendance.index') }}",
                type: 'GET',
                data: function(d) {
                    d.tab = 'duration';
                    $.extend(d, filterParams);
                }
            },
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                 "<'row'<'col-sm-12'rt>>" +
                 "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            order: [],
            autoWidth: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: '<div class="spinner-border text-primary spinner-border-sm" role="status"></div> Memuat data durasi...',
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    } else {
        tblDur.columns.adjust();
    }
});

// Hitung ulang lebar kolom saat tab dibuka
$('a[data-bs-toggle="pill"], button[data-bs-toggle="pill"]').on('shown.bs.tab', function () {
    $($.fn.dataTable.tables({ visible: true, api: true })).DataTable().columns.adjust();
});
</script>
@endsection