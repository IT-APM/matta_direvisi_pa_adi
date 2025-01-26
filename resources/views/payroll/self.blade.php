@extends('layouts.simple.master')

@section('title', 'Payroll')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}"> --}}
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
<h3>Payroll Slip</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Slip</li>
@endsection
@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(count($payroll)>0 || count($payroll_h)>0)<h6 class="text-danger">Catatan: Masukkan tanggal lahir anda dengan format ddmmyyyy sebagai password untuk membuka file pdf slip gaji.<br>Contoh: Jika tanggal lahir anda adalah 2 Maret 2004 maka passwordnya adalah 02032004.</h6>@endif
                    <div class="table-responsive mt-3">
                        <table class="display nowrap" id="_datatable1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Slip Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($payroll as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $bulan[$data->bulan - 1] }}</td>
                                    <td>{{ $data->_nik->nama }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td><a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                                </tr>
                                @endforeach
                                @foreach ($payroll_h as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $bulan[$data->bulan - 1] }}</td>
                                    <td>{{ $data->_nik->nama }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td><a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/sweet-alert/sweetalert.minjs') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/appjs') }}"></script> --}}
<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
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