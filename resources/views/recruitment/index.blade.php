@extends('layouts.simple.master')

@section('title', 'Recruitment')

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
<h3>List Of Recruitment</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Recruitment</li>
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
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Pengaju</th>
                                    <th>Posisi</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Mulai Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($trx as $data)
                                @php
                                    $where = ['id_kebutuhan' => $data->id];
                                    $recruitment = App\Models\TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('recruitment.show', [Crypt::encrypt(array($data->id, 1))]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @if(!empty($recruitment))<br/><a href="{{ route('recruitment.apply', [Crypt::encrypt(array($recruitment->id, 'apply'))]) }}" data-toggle="tooltip" title="Apply" target="_blank"><i class="icon-eye"></i></a>@endif
                                    </td>
                                    <td>{{ $data->_pengaju->nama }} / {{ $data->_pengaju->nik }}</td>
                                    <td>{{ $data->_pekerjaan->pekerjaan }}</td>
                                    <td>{{ $data->_status_kerja->status_kerja }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d-m-Y') }}</td>
                                    <td>
                                        @if(!empty($recruitment))
                                        <a class="btn btn-success" href="{{ route('recruitment.show', [Crypt::encrypt(array($data->id, $recruitment->id_status))]) }}" data-toggle="tooltip" title="{{ $recruitment->_status->status }}" target="_blank">{{ $recruitment->_status->status }}</a>
                                        @else
                                        <a class="btn btn-info" href="{{ route('recruitment.show', [Crypt::encrypt(array($data->id, 1))]) }}" data-toggle="tooltip" title="Process">Process</a>
                                        @endif
                                    </td>
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