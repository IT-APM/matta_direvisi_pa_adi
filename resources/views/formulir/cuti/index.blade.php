@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Cuti')

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
<h3>Formulir Permohonan Cuti</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Cuti</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <a href="{{ route('cuti.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('cuti.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6" for="nama">Karyawan</label>
                                    <input name="nama" id="nama" class="form-control" type="text" value="{{ $nama }}">
                                    @if(auth()->user()->userrole == 'hc')
                                    <label class="h6 mt-2" for="atasan">Layer 1</label>
                                    <input name="atasan" id="atasan" class="form-control" type="text" value="{{ $atasan }}">
                                    @endif
                                    {{-- <br/> --}}
                                    <label class="h6 mt-2">Tanggal Cuti</label>
                                    <br/>Dari <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                    Sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_cuti(this)" /> Check All
                                    <label class="d-block" for="status_cuti">
                                        @foreach ($status_cuti as $data)
                                        <input class="checkbox_animated" id="status_cuti[]" name="status_cuti[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_cuti)) checked @endif> {{ $data->status_cuti }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                                <div class="col-sm-5">
                                    <label class="h6">Tipe Cuti</label>&nbsp;<input type="checkbox" onClick="toggle_tipe_cuti(this)" /> Check All
                                    <label class="d-block" for="tipe_cuti">
                                        @foreach ($tipe_cuti as $data)
                                        <input class="checkbox_animated" id="tipe_cuti[]" name="tipe_cuti[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_tipe_cuti)) checked @endif> {{ $data->tipe_cuti }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
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
                                    <th>Action</th>
                                    <th>Tipe</th>
                                    <!-- <th>Periode</th> -->
                                    <th>Karyawan</th>
                                    <th>NIP</th>
                                    <th>PIC Pengganti</th>
                                    <th>Layer 1</th>
                                    <th>Tanggal Cuti</th>
                                    <th>Status</th>
                                    <th>Sisa Cuti</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @inject('sisaCuti', 'App\Http\Controllers\TrxCutiController')
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('cuti.show', [$data->id, 'view' => 'detail']) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('cuti.show', [$data->id, 'view' => 'pdf']) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td>{{ $data->_tipe->tipe_cuti }}</td>
                                    <!-- <td>{{ $data->periode_cuti }}</td> -->
                                    <td>{{ $data->_karyawan->nama }}</td>
                                    <td>{{ $data->_karyawan->nik }}</td>
                                    <td>
                                        @foreach (explode(';', $data->pic_property) as $value)
                                        {{ json_decode($value, true)['nama'] }} <br />
                                        @endforeach
                                    </td>
                                    <td>{{ $data->_atasan->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }}<br />s/d<br />{{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
                                    <td>{{ $data->_status->status_cuti }}</td>
                                    <td>{{ $sisaCuti::getSisaCuti($data->nik_karyawan, $data->periode_cuti, $data->id) }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Periode</th>
                                    <th>Karyawan</th>
                                    <th>NIP</th>
                                    <th>PIC Pengganti</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th>Sisa Cuti</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> -->
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
function toggle_status_cuti(source) {
    checkboxes = document.getElementsByName('status_cuti[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_tipe_cuti(source) {
    checkboxes = document.getElementsByName('tipe_cuti[]');
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