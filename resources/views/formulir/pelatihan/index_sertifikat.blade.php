@extends('layouts.simple.master')

@section('title', 'Daftar Sertifikat')

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
<h3>Daftar Sertifikat</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pengajuan Pelatihan</li>
<li class="breadcrumb-item active">Daftar Sertifikat</li>
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
                    {{-- <a href="{{ route('pelatihan.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey(4), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('list_certificate')]) }}" class="btn btn-primary" type="button"><i class="fa fa-file-text-o" aria-hidden="true"></i> List of Certificate</a>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('pelatihan.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> Check All
                                    <label class="d-block" for="status_pengajuan">
                                        @foreach($status_pengajuan as $data)
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_pengajuan)) checked @endif> {{ $data->status_pengajuan }}<br/>
                                        @endforeach
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
                    <br/><br/> --}}
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
                                @foreach($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>
                                        {{-- jika ada id pelatihan --}}
                                        @if(!empty($data->id_trx))
                                        <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @else
                                            {{-- jika tidak ada id pelatihan maka gunakan link dari gdrive --}}
                                            @if(!empty($data->formulir_link))
                                            <a href="{{ $data->formulir_link }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $data->jenis }}</td>
                                    <td>{{ $data->penerbit }}</td>
                                    <td>{{ $data->kta }}</td>
                                    <td>{{ $data->judul }}@if(!empty($data->int_ext))<br/> ({{ $data->int_ext }}) @endif</td>
                                    <td>{{ $data->lembaga }}</td>
                                    <td>{{ $data->biaya }}</td>
                                    <td>
                                        @if(!empty($data->tanggal_mulai) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_mulai))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_mulai)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($data->tanggal_akhir) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_akhir))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_akhir)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>{{ $data->nomor_sertifikat }}</td>
                                    <td>
                                        @if(!empty($data->tanggal_sertifikat) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_sertifikat))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_sertifikat)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($data->tanggal_kadaluarsa) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_kadaluarsa))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_kadaluarsa)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>@if(!empty($data->sertifikat_link))<a href="{{ $data->sertifikat_link }}" data-toggle="tooltip" title="Sertifikat" target="_blank"><i class="fa fa-file-text-o"></i></a>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('pelatihan.index') }}" class="btn btn-warning mt-2" type="button">Back</a>
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
@endsection