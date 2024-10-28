@extends('layouts.simple.master')
@section('title', 'Formulir Reimbursement')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Add</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir Reimbursement</li>
<li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Plafon</h5>
                </div>
                <div class="card-body typography">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>Tahun</h6>
                                    </th>
                                    <th>
                                        <h6>Kesehatan</h6>
                                    </th>
                                    <th>
                                        <h6>Frame</h6>
                                    </th>
                                    <th>
                                        <h6>Lensa</h6>
                                    </th>
                                    <th>
                                        <h6>Kehamilan</h6>
                                    </th>
                                    <th>
                                        <h6>Persalinan Normal</h6>
                                    </th>
                                    <th>
                                        <h6>Persalinan Caesar</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $plafon->periode }}</td>
                                    <td>Rp. {{ number_format($plafon->kesehatan, 2) }}</td>
                                    <td>Rp. {{ number_format($plafon->frame, 2) }}</td>
                                    <td>Rp. {{ number_format($plafon->lensa, 2) }}</td>
                                    <td>Rp. {{ number_format($plafon->kehamilan, 2) }}</td>
                                    <td>Rp. {{ number_format($plafon->persalinan_normal, 2) }}</td>
                                    <td>Rp. {{ number_format($plafon->persalinan_caesar, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="{{ route('reimburse.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return validateForm()">
                    @csrf
                    <!--  -->
                    <div class="card-header">
                        <h5>Isian Reimbursement</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <h6>Hubungan Dengan Karyawan</h6>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" id="_hubungan" name="_hubungan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}">{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- 1=Karyawan --}}
                        <div class="row g-3" id="reimburse1">
                            <input type="hidden" id="_hubungan1" name="_hubungan1" value="1">
                            <div class="col-md-3">
                                <label class="form-label">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="hubungan1" name="hubungan1" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == 1) selected @endif>{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nama Pasien</label>
                                <input name="nama_pasien1" id="nama_pasien1" class="form-control" type="text" required="" value="{{ $karyawan->nama }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Layanan</label>
                                <select class="form-select" id="layanan1" name="layanan1" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($layanan as $data)
                                    <option value="{{ $data->id }}">{{ $data->layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Layanan</label>
                                <input class="form-control" name="tanggal1" id="tanggal1" type="date" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nominal Klaim</label>
                                <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim1" id="jumlah_klaim1" class="form-control numbers" required="">
                            </div>
                            <div class="col-md-1">
                                <a id='delete_row1' data-toggle="tooltip" title="Delete" class="pull-right btn btn-danger mt-4"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        {{-- 3=Istri dari Karyawan --}}
                        <div class="row g-3" id="reimburse3">
                            <input type="hidden" id="_hubungan3" name="_hubungan3" value="3">
                            <div class="col-md-3">
                                <label class="form-label">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="hubungan3" name="hubungan3" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == 3) selected @endif>{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nama Pasien</label>
                                <input name="nama_pasien3" id="nama_pasien3" class="form-control" type="text" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Layanan</label>
                                <select class="form-select" id="layanan3" name="layanan3" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($layanan_istri as $data)
                                    <option value="{{ $data->id }}">{{ $data->layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Layanan</label>
                                <input class="form-control" name="tanggal3" id="tanggal3" type="date" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nominal Klaim</label>
                                <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim3" id="jumlah_klaim3" class="form-control numbers" required="">
                            </div>
                            <div class="col-md-1">
                                <a id='delete_row3' data-toggle="tooltip" title="Delete" class="pull-right btn btn-danger mt-4"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        {{-- 4=Anak ke-1 --}}
                        <div class="row g-3" id="reimburse4">
                            <input type="hidden" id="_hubungan4" name="_hubungan4" value="4">
                            <div class="col-md-3">
                                <label class="form-label">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="hubungan4" name="hubungan4" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == 4) selected @endif>{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nama Pasien</label>
                                <input name="nama_pasien4" id="nama_pasien4" class="form-control" type="text" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Layanan</label>
                                <select class="form-select" id="layanan4" name="layanan4" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($layanan_anak as $data)
                                    <option value="{{ $data->id }}">{{ $data->layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Layanan</label>
                                <input class="form-control" name="tanggal4" id="tanggal4" type="date" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nominal Klaim</label>
                                <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim4" id="jumlah_klaim4" class="form-control numbers" required="">
                            </div>
                            <div class="col-md-1">
                                <a id='delete_row4' data-toggle="tooltip" title="Delete" class="pull-right btn btn-danger mt-4"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        {{-- 5=Anak ke-2 --}}
                        <div class="row g-3" id="reimburse5">
                            <input type="hidden" id="_hubungan5" name="_hubungan5" value="5">
                            <div class="col-md-3">
                                <label class="form-label">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="hubungan5" name="hubungan5" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == 5) selected @endif>{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nama Pasien</label>
                                <input name="nama_pasien5" id="nama_pasien5" class="form-control" type="text" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Layanan</label>
                                <select class="form-select" id="layanan5" name="layanan5" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($layanan_anak as $data)
                                    <option value="{{ $data->id }}">{{ $data->layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Layanan</label>
                                <input class="form-control" name="tanggal5" id="tanggal5" type="date" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nominal Klaim</label>
                                <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim5" id="jumlah_klaim5" class="form-control numbers" required="">
                            </div>
                            <div class="col-md-1">
                                <a id='delete_row5' data-toggle="tooltip" title="Delete" class="pull-right btn btn-danger mt-4"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        {{-- 6=Anak ke-3 --}}
                        <div class="row g-3" id="reimburse6">
                            <input type="hidden" id="_hubungan6" name="_hubungan6" value="6">
                            <div class="col-md-3">
                                <label class="form-label">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="hubungan6" name="hubungan6" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == 6) selected @endif>{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nama Pasien</label>
                                <input name="nama_pasien6" id="nama_pasien6" class="form-control" type="text" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Layanan</label>
                                <select class="form-select" id="layanan6" name="layanan6" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($layanan_anak as $data)
                                    <option value="{{ $data->id }}">{{ $data->layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Layanan</label>
                                <input class="form-control" name="tanggal6" id="tanggal6" type="date" required="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nominal Klaim</label>
                                <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim6" id="jumlah_klaim6" class="form-control numbers" required="">
                            </div>
                            <div class="col-md-1">
                                <a id='delete_row6' data-toggle="tooltip" title="Delete" class="pull-right btn btn-danger mt-4"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        <br/>Catatan:<br/>
                        1. Jika kolom Hubungan Dengan Karyawan dipilih Karyawan maka kolom Nama Pasien akan otomatis terisi nama karyawan.
                        <br/>2. Jika kolom Layanan dipilih Kehamilan, Persalinan-Normal atau Persalinan-Caesar maka kolom Nominal Klaim akan otomatis terisi plafon.
                        {{--  --}}
                    </div>
                    <!-- <hr/> -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Isian Lampiran</h5>
                            </div>
                            <div class="card-body typography">
                                <div class="table-responsive">
                                    <table class="table" id="trx_reimburse_lampiran">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Lampiran Pengajuan</h6>
                                                </th>
                                                <th>
                                                    <h6>File Lampiran</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="reimburse_lampiran0">
                                                <td>
                                                    <select class="form-select" id="lampiran[]" name="lampiran[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        @foreach ($lampiran as $data)
                                                        <option value="{{ $data->id }}">{{ $data->lampiran }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="file_lampiran[]" id="file_lampiran[]" class="form-control" type="file" required="">
                                                </td>
                                            </tr>
                                            <tr id="reimburse_lampiran1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button id="add_row_lampiran" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id='delete_row_lampiran' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                                <br /><br />
                                Note: Lampiran wajib diisi dan dilengkapi.
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Keterangan</h5>
                            </div>
                            <div class="card-body typography">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-body typography">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<!--  -->
<!-- source: https://blog.quickadminpanel.com/master-detail-form-in-laravel-jquery-create-order-with-products/ -->
<script type="text/javascript">
function use_number(node) {
  var empty_val = false;
  const value = node.value;
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
  if (node.value == '')
    empty_val = true;
  node.type = 'number';
  if (!empty_val)
    node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
}
// 
function use_text(node) {
  var empty_val = false;
  const value = Number(node.value);
  if (node.value == '')
    empty_val = true;
  node.type = 'text';
  if (!empty_val)
    node.value = value.toLocaleString('en');  // or other formatting
}
// 
$(document).ready(function() {
    //  
    let row_number_lampiran = 1;
    $("#add_row_lampiran").click(function(e) {
        e.preventDefault();
        let new_row_number_lampiran = row_number_lampiran - 1;
        $('#reimburse_lampiran' + row_number_lampiran).html($('#reimburse_lampiran' + new_row_number_lampiran).html()).find('td:first-child');
        $('#trx_reimburse_lampiran').append('<tr id="reimburse_lampiran' + (row_number_lampiran + 1) + '"></tr>');
        row_number_lampiran++;
    });

    $("#delete_row_lampiran").click(function(e) {
        e.preventDefault();
        if (row_number_lampiran > 1) {
            $("#reimburse_lampiran" + (row_number_lampiran - 1)).html('');
            row_number_lampiran--;
        }
    });
    // default semua hide dan disable=true
    reimburse1(0);
    reimburse3(0);
    reimburse4(0);
    reimburse5(0);
    reimburse6(0);
    // 
    $("#_hubungan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 1) {
            reimburse1(1);
        }else if (selectedValue == 3) {
            reimburse3(1);
        }else if (selectedValue == 4) {
            reimburse4(1);
        }else if (selectedValue == 5) {
            reimburse5(1);
        }else if (selectedValue == 6) {
            reimburse6(1);
        }
    });
    // 1=Karyawan
    $("#delete_row1").click(function() {
        // $('#_hubungan').prop('selectedIndex', 0);
        $('#jumlah_klaim1').val('')
        reimburse1(0);
    });
    // 3=Istri dari Karyawan
    $("#delete_row3").click(function() {
        // $('#_hubungan').prop('selectedIndex', 0);
        $('#jumlah_klaim3').val('')
        reimburse3(0);
    });
    // 4=Anak ke-1
    $("#delete_row4").click(function() {
        // $('#_hubungan').prop('selectedIndex', 0);
        $('#jumlah_klaim4').val('')
        reimburse4(0);
    });
    // 5=Anak ke-2
    $("#delete_row5").click(function() {
        // $('#_hubungan').prop('selectedIndex', 0);
        $('#jumlah_klaim5').val('')
        reimburse5(0);
    });
    // 6=Anak ke-3
    $("#delete_row6").click(function() {
        // $('#_hubungan').prop('selectedIndex', 0);
        $('#jumlah_klaim6').val('')
        reimburse6(0);
    });
    // 1=Karyawan
    function reimburse1(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#reimburse1').show();
            $("#hubungan1").prop('disabled', true);
            $("#_hubungan1").prop('disabled', false);
            $("#nama_pasien1").prop('disabled', false);
            $("#layanan1").prop('disabled', false);
            $("#tanggal1").prop('disabled', false);
            $("#jumlah_klaim1").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#reimburse1').hide();
            $("#hubungan1").prop('disabled', true);
            $("#_hubungan1").prop('disabled', true);
            $("#nama_pasien1").prop('disabled', true);
            $("#layanan1").prop('disabled', true);
            $("#tanggal1").prop('disabled', true);
            $("#jumlah_klaim1").prop('disabled', true);
        }
    }
    // 3=Istri dari Karyawan
    function reimburse3(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#reimburse3').show();
            $("#hubungan3").prop('disabled', true);
            $("#_hubungan3").prop('disabled', false);
            $("#nama_pasien3").prop('disabled', false);
            $("#layanan3").prop('disabled', false);
            $("#tanggal3").prop('disabled', false);
            $("#jumlah_klaim3").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#reimburse3').hide();
            $("#hubungan3").prop('disabled', true);
            $("#_hubungan3").prop('disabled', true);
            $("#nama_pasien3").prop('disabled', true);
            $("#layanan3").prop('disabled', true);
            $("#tanggal3").prop('disabled', true);
            $("#jumlah_klaim3").prop('disabled', true);
        }
    }
    // 4=Anak ke-1
    function reimburse4(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#reimburse4').show();
            $("#hubungan4").prop('disabled', true);
            $("#_hubungan4").prop('disabled', false);
            $("#nama_pasien4").prop('disabled', false);
            $("#layanan4").prop('disabled', false);
            $("#tanggal4").prop('disabled', false);
            $("#jumlah_klaim4").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#reimburse4').hide();
            $("#hubungan4").prop('disabled', true);
            $("#_hubungan4").prop('disabled', true);
            $("#nama_pasien4").prop('disabled', true);
            $("#layanan4").prop('disabled', true);
            $("#tanggal4").prop('disabled', true);
            $("#jumlah_klaim4").prop('disabled', true);
        }
    }
    // 5=Anak ke-2
    function reimburse5(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#reimburse5').show();
            $("#hubungan5").prop('disabled', true);
            $("#_hubungan5").prop('disabled', false);
            $("#nama_pasien5").prop('disabled', false);
            $("#layanan5").prop('disabled', false);
            $("#tanggal5").prop('disabled', false);
            $("#jumlah_klaim5").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#reimburse5').hide();
            $("#hubungan5").prop('disabled', true);
            $("#_hubungan5").prop('disabled', true);
            $("#nama_pasien5").prop('disabled', true);
            $("#layanan5").prop('disabled', true);
            $("#tanggal5").prop('disabled', true);
            $("#jumlah_klaim5").prop('disabled', true);
        }
    }
    // 6=Anak ke-3
    function reimburse6(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#reimburse6').show();
            $("#hubungan6").prop('disabled', true);
            $("#_hubungan6").prop('disabled', false);
            $("#nama_pasien6").prop('disabled', false);
            $("#layanan6").prop('disabled', false);
            $("#tanggal6").prop('disabled', false);
            $("#jumlah_klaim6").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#reimburse6').hide();
            $("#hubungan6").prop('disabled', true);
            $("#_hubungan6").prop('disabled', true);
            $("#nama_pasien6").prop('disabled', true);
            $("#layanan6").prop('disabled', true);
            $("#tanggal6").prop('disabled', true);
            $("#jumlah_klaim6").prop('disabled', true);
        }
    }
    // 
    $("#layanan1").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $('#jumlah_klaim1').val('{{ $plafon->kehamilan }}');
        }else if (selectedValue == 5) {
            $('#jumlah_klaim1').val('{{ $plafon->persalinan_normal }}');
        }else if (selectedValue == 6) {
            $('#jumlah_klaim1').val('{{ $plafon->persalinan_caesar }}');
        }
    });
    // 
    $("#layanan3").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 4) {
            $('#jumlah_klaim3').val('{{ $plafon->kehamilan }}');
        }else if (selectedValue == 5) {
            $('#jumlah_klaim3').val('{{ $plafon->persalinan_normal }}');
        }else if (selectedValue == 6) {
            $('#jumlah_klaim3').val('{{ $plafon->persalinan_caesar }}');
        }
    });
});
    // 
    function validateForm() {
        var check = $('#jumlah_klaim1').val()+$('#jumlah_klaim3').val()+$('#jumlah_klaim4').val()+$('#jumlah_klaim5').val()+$('#jumlah_klaim6').val();
        // alert(check);
        if(check <= 0){
            // $('#_hubungan').prop('selectedIndex', 0);
            alert('Isian Reimbursement tidak boleh kosong');
            return false;
        }
        else{
            return confirm('Are you sure want to submit this data?');
        }
    }
</script>
@endsection