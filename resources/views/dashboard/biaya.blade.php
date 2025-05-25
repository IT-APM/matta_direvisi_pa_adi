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
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Biaya Gaji</h5>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('dashboard.biaya', 0) }}" method="get" enctype="multipart/form-data">
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <label class="h6">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" /> Check All
                                        <label class="d-block" for="tahun">
                                            @foreach ($tahun as $data)
                                                <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="{{ $data['tahun'] }}" @if (in_array($data['tahun'], $arr_tahun)) checked @endif>
                                                {{ $data['tahun'] }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Bulan</label>&nbsp;<input type="checkbox" onClick="toggle_bulan(this)" /> Check All
                                        <label class="d-block" for="bulan">
                                            @foreach ($bulan as $data)
                                                <input class="checkbox_animated" id="bulan[]" name="bulan[]" type="checkbox" value="{{ $data['angka'] }}" @if (in_array($data['angka'], $arr_bulan)) checked @endif>
                                                {{ $data['bulan'] }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_status_karyawan(this)" /> Check All
                                        <label class="d-block" for="status_karyawan">
                                            @foreach ($status_karyawan as $data)
                                                <input class="checkbox_animated" id="status_karyawan[]" name="status_karyawan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_status_karyawan)) checked @endif>
                                                {{ $data->status_karyawan }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                        <label class="d-block" for="kode_perusahaan">
                                            @foreach ($perusahaan as $data)
                                                <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan()" value="{{ $data->id }}" @if (in_array($data->id, $arr_perusahaan)) checked @endif>
                                                {{ $data->nama }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                        <label class="d-block" for="kode_divisi">
                                            <div id="div_kode_divisi">
                                                @foreach ($divisi as $data)
                                                    <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi()" value="{{ $data->id }}" @if (in_array($data->id, $arr_divisi)) checked @endif>
                                                    {{ $data->nama }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_kode_departemen(this)" /> Check All
                                        <label class="d-block" for="kode_departemen">
                                            <div id="div_kode_departemen">
                                                @foreach ($departemen as $data)
                                                    <input class="checkbox_animated" id="kode_departemen[]" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen()" value="{{ $data->id }}" @if (in_array($data->id, $arr_departemen)) checked @endif>
                                                    {{ $data->nama }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_kode_sub_departemen(this)" /> Check All
                                        <label class="d-block" for="kode_sub_departemen">
                                            <div id="div_kode_sub_departemen">
                                                @foreach ($sub_departemen as $data)
                                                    <input class="checkbox_animated" id="kode_sub_departemen[]" name="kode_sub_departemen[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_sub_departemen)) checked @endif>
                                                    {{ $data->nama }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('dashboard.biaya', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--  --}}
                        @php
                            $total_biaya_gaji = 0;
                            $kompensasi_kkwt = 0;
                            $group_by = [];
                        @endphp
                        @foreach ($payroll as $data)
                        @php
                            $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                            $arr_id = [1,2,3,4,5];
                            $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                            $total_tunjangan = $add->sum('angka');
                            $arr_id = [6,7,8,9,21];
                            $_add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                            $_total_tunjangan = $_add->sum('angka');
                            $total_biaya_gaji += $data->gapok + $total_tunjangan + $_total_tunjangan;
                            // 9=Kompensasi KKWT
                            $arr_id = [9];
                            $_add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                            $kompensasi_kkwt += round($_add->sum('angka'));
                            // 
                            array_push($group_by, ['perusahaan'=>$data->perusahaan, 'divisi'=>$data->divisi, 'departemen'=>$data->departemen, 'subdepartemen'=>$data->subdepartemen, 'status_karyawan'=>$data->status_karyawan, 'total_biaya_gaji'=>$data->gapok + $total_tunjangan + $_total_tunjangan]);
                        @endphp
                        @endforeach
                        @php
                            $biaya_gaji = $total_biaya_gaji - $kompensasi_kkwt;
                        @endphp
                        {{--  --}}
                        <div class="row mt-2">
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                <h5>Jumlah Karyawan</h5>
                                                <h5>@if(!empty($payroll)) {{ $payroll->count() }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                <h5>Biaya Gaji</h5>
                                                <h5>Rp. @if(!empty($payroll)) {{ number_format($biaya_gaji, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                <h5>Kompensasi KKWT</h5>
                                                <h5>Rp. @if(!empty($payroll)) {{ number_format($kompensasi_kkwt, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                <h5>Total Biaya Gaji</h5>
                                                <h5>Rp. @if(!empty($payroll)) {{ number_format($total_biaya_gaji, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  --}}
                        <div class="row">
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Perusahaan</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $perusahaan = App\Models\Perusahaan::whereIn('id', $arr_perusahaan)->orderBy('nama', 'asc')->groupBy('nama')->get();
                                                $grouped = collect($group_by)->groupBy('perusahaan');
                                            @endphp
                                            @foreach ($perusahaan as $data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($data->nama);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($data->nama)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format($jumlah, 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Direktorat</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $divisi = App\Models\Divisi::whereIn('id', $arr_divisi)->orderBy('nama', 'asc')->groupBy('nama')->get();
                                                $grouped = collect($group_by)->groupBy('divisi');
                                            @endphp
                                            @foreach ($divisi as $data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($data->nama);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($data->nama)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format($jumlah, 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <hr class="mt-2 mb-2" />
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Divisi</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $departemen = App\Models\Departemen::whereIn('id', $arr_departemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
                                                $grouped = collect($group_by)->groupBy('departemen');
                                            @endphp
                                            @foreach ($departemen as $data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($data->nama);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($data->nama)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format($jumlah, 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Departemen</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $subdepartemen = App\Models\SubDepartemen::whereIn('id', $arr_sub_departemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
                                                $grouped = collect($group_by)->groupBy('subdepartemen');
                                            @endphp
                                            @foreach ($subdepartemen as $data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($data->nama);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($data->nama)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format($jumlah, 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <hr class="mt-2 mb-2" />
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Status Kerja</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $status_karyawan = App\Models\KaryawanStatus::whereIn('id', $arr_status_karyawan)->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
                                                $grouped = collect($group_by)->groupBy('status_karyawan');
                                                // dd($status_karyawan);
                                            @endphp
                                            @foreach ($status_karyawan as $data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($data->status_karyawan);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($data->status_karyawan)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->status_karyawan }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format($jumlah, 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
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
<script type="text/javascript">
// 
$('#_datatable1').DataTable({
	dom: 'lfrtip',
    // dom: 'rtip',
});
// 
$('#_datatable2').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable3').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable4').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable5').DataTable({
	dom: 'lfrtip',
});
// 
function get_kode_perusahaan() {
    getDivisi();
}
// 
function get_kode_divisi() {
    getDepartemen();
}
// 
function get_kode_departemen() {
    getSubDepartemen();
}
// 
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_bulan(source) {
    checkboxes = document.getElementsByName('bulan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('kode_perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDivisi();
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('kode_divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDepartemen();
}
// 
function toggle_kode_departemen(source) {
    checkboxes = document.getElementsByName('kode_departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getSubDepartemen();
}
// 
function toggle_kode_sub_departemen(source) {
    checkboxes = document.getElementsByName('kode_sub_departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_karyawan(source) {
    checkboxes = document.getElementsByName('status_karyawan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function getDivisi() {
    var _kode_perusahaan = [];
    $('input[id="kode_perusahaan[]"]:checked').each(function() {
        _kode_perusahaan.push($(this).val());
    });
    $('#div_kode_divisi').empty();
    $('#div_kode_departemen').empty();
    $('#div_kode_sub_departemen').empty();
    // AJAX request 
    $.ajax({
        url: "{{ route('getDivisi') }}" + "/" + _kode_perusahaan,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_divisi[]' name='kode_divisi[]' type='checkbox' onClick='get_kode_divisi()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_divisi").append(option);
                }
            }
        }
    });
}
// 
function getDepartemen() {
    var _kode_divisi = [];
    $('input[id="kode_divisi[]"]:checked').each(function() {
        _kode_divisi.push($(this).val());
    });
    $('#div_kode_departemen').empty();
    $('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "{{ route('getDepartemen') }}" + "/" + _kode_divisi,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_departemen[]' name='kode_departemen[]' type='checkbox' onClick='get_kode_departemen()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_departemen").append(option);
                }
            }
        }
    });
}
// 
function getSubDepartemen() {
    var _kode_departemen = [];
    $('input[id="kode_departemen[]"]:checked').each(function() {
        _kode_departemen.push($(this).val());
    });
    $('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "{{ route('getSubDepartemen') }}" + "/" + _kode_departemen,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_sub_departemen[]' name='kode_sub_departemen[]' type='checkbox' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_sub_departemen").append(option);
                }
            }
        }
    });
}
// 
</script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
