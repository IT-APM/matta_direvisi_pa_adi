@extends('layouts.simple.master')

@section('title', 'Formulir Kebutuhan Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>{{ __('lang.Formulir Kebutuhan Karyawan') }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">{{ __('lang.Formulir') }}</li>
<li class="breadcrumb-item active">{{ __('lang.Kebutuhan Karyawan') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <a href="{{ route('kebutuhan.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('lang.Tambah Baru') }}</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> {{ __('lang.Saring') }}</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('kebutuhan.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6">{{ __('lang.Status Pengajuan') }}</label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> {{ __('lang.Pilih Semua') }}
                                    <label class="d-block" for="status_pengajuan">
                                        @foreach ($status_pengajuan as $data)
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_pengajuan)) checked @endif> {{ $data->status_pengajuan }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">{{ __('lang.Area Kerja') }}</label>&nbsp;<input type="checkbox" onClick="toggle_id_area(this)" /> {{ __('lang.Pilih Semua') }}
                                    <label class="d-block" for="id_area">
                                        @foreach ($id_area as $data)
                                        <input class="checkbox_animated" id="id_area[]" name="id_area[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_area)) checked @endif> {{ $data->propinsi }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">{{ __('lang.Jenis Permintaan') }}</label>&nbsp;<input type="checkbox" onClick="toggle_id_jenis_permintaan(this)" /> {{ __('lang.Pilih Semua') }}
                                    <label class="d-block" for="id_jenis_permintaan">
                                        @foreach ($id_jenis_permintaan as $data)
                                        <input class="checkbox_animated" id="id_jenis_permintaan[]" name="id_jenis_permintaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_jenis_permintaan)) checked @endif> {{ $data->jenis }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {{ __('lang.Saring') }}</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> {{ __('lang.Atur Ulang') }}</button>
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
                                    <th>{{ __('lang.Aksi') }}</th>
                                    <th>{{ __('lang.Area Kerja') }}</th>
                                    <th>{{ __('lang.Area Kerja Kota') }}</th>
                                    <th>{{ __('lang.Pengaju') }}</th>
                                    <th>{{ __('lang.NIP') }}</th>
                                    <th>{{ __('lang.Nama Jabatan') }}</th>
                                    <th>{{ __('lang.Status Kerja') }}</th>
                                    <th>{{ __('lang.Tanggal Mulai Bekerja') }}</th>
                                    <th>{{ __('lang.Jenis Permintaan') }}</th>
                                    <th>{{ __('lang.Jumlah (Orang)') }}</th>
                                    <th>{{ __('lang.Jenis Kelamin') }}</th>
                                    <th>{{ __('lang.Status Pengajuan') }}</th>
                                    <th>{{ __('lang.Waktu Pembuatan') }}</th>
                                    <th>{{ __('lang.Waktu Selesai') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @can('hc')
                                        <br /><a href="{{ route('kebutuhan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('doc')]) }}" data-toggle="tooltip" title="Document"><i class="fa fa-file-text-o"></i></a>
                                        @endcan

                                    </td>
                                    <td>{{ $data->_area->propinsi }}</td>
                                    <td>@if (!empty($data->id_kota)) {{ $data->_kota->kota }} @endif</td>
                                    <td>{{ $data->_pengaju->nama }}</td>
                                    <td>{{ $data->_pengaju->nik }}</td>
                                    <td>{{ $data->_pekerjaan->pekerjaan }}</td>
                                    <td>{{ $data->_status_kerja->status_kerja }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $data->_jenis_permintaan->jenis }}</td>
                                    <td>{{ $data->jumlah }}</td>
                                    <td>{{ $data->_kelamin->kelamin }}</td>
                                    <td>{{ $data->_status_pengajuan->status_pengajuan }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>@if ($data->status_pengajuan==10) {{ $data->updated_at }} @endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
@endsection