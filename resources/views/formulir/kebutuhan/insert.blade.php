@extends('layouts.simple.master')
@section('title', 'Formulir Kebutuhan Karyawan')

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
<li class="breadcrumb-item">Formulir Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <form action="{{ route('kebutuhan.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return cekIsian()">
                    @csrf
                    <div class="row">
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Kebutuhan Rekruitmen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- <button type="button" onclick="cekIsian()">cekIsian</button> -->
                                    <label class="form-label" for="id_pekerjaan">Nama Jabatan*</label>
                                    <div class="col-md-10 pb-2">
                                        <!--  -->
                                        <select class="form-select col-sm-12 js-example-basic-single" id="id_pekerjaan" name="id_pekerjaan" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach ($pekerjaan as $data)
                                            <option value="{{ $data->id }}">{{ $data->pekerjaan }}
                                                ({{ $data->_bidang->bidang }})</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback txt-danger">Nama Jabatan is required.</div>
                                    </div>
                                    <div class="col-md-2 pb-2 text-end">
                                        <a class="btn btn-primary" href="{{ route('pekerjaan_master.index') }}" data-toggle="tooltip" title="Add new"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tugas">Tugas*</label>
                                    <textarea class="form-control" id="tugas" name="tugas" rows="3" required=""></textarea>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="wewenang">Wewenang*</label>
                                    <textarea class="form-control" id="wewenang" name="wewenang" rows="3" required=""></textarea>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggung_jawab">Tanggung Jawab*</label>
                                    <textarea class="form-control" id="tanggung_jawab" name="tanggung_jawab" rows="3" required=""></textarea>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_level">Level*</label>
                                    <select class="form-select col-sm-12" id="id_level" name="id_level" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($level as $data)
                                        <option value="{{ $data->id }}">{{ $data->level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_status_kerja">Status Kerja*</label>
                                    <select class="form-select col-sm-12" id="id_status_kerja" name="id_status_kerja" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($status_kerja as $data)
                                        <option value="{{ $data->id }}">{{ $data->status_kerja }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2" id="div_waktu_kontrak">
                                    <label class="form-label" for="waktu_kontrak">Waktu Kontrak (Bulan)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="waktu_kontrak" id="waktu_kontrak" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="jumlah">Jumlah (Orang)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="jumlah" id="jumlah" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_kelamin">Jenis Kelamin*</label>
                                    <select class="form-select col-sm-12" id="id_kelamin" name="id_kelamin" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($kelamin as $data)
                                        <option value="{{ $data->id }}">{{ $data->kelamin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_area">Area Kerja*</label>
                                    <select class="form-select col-sm-12" id="id_area" name="id_area" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($area as $data)
                                        <option value="{{ $data->id }}">{{ $data->propinsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2" id="div_kota">
                                    <label class="form-label" for="id_kota">Area Kerja Kota</label>
                                    <select class="form-select col-sm-12" id="id_kota" name="id_kota" >
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_jenis_permintaan">Jenis Permintaan*</label>
                                    <select class="form-select col-sm-12" id="id_jenis_permintaan" name="id_jenis_permintaan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($jenis_permintaan as $data)
                                        <option value="{{ $data->id }}">{{ $data->jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2" id="div_nik_karyawan">
                                    <input type="hidden" id="_nik_karyawan_" name="_nik_karyawan_" >
                                    <label class="form-label" for="nik_karyawan">Karyawan Penggantian*<br/>(Sesuai jumlah pada isian Jumlah (Orang))</label>
                                    <select class="js-example-basic-multiple col-sm-12" id="nik_karyawan" name="nik_karyawan" required="" multiple="multiple">
                                        <!-- <option selected="" disabled="" value="">Pilih...</option> -->
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Karyawan Penggantian is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_rencana">Apakah sudah masuk dalam rencana <span id="str_jenis_permintaan" name="str_jenis_permintaan">penambahan</span> pegawai?*</label>
                                    <select class="form-select col-sm-12" id="id_rencana" name="id_rencana" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <option value="0">Belum</option>
                                        <option value="1">Sudah</option>
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="penjelasan">Alasan Permintaan*</label>
                                    <textarea class="form-control" id="penjelasan" name="penjelasan" rows="4" required=""></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Kualifikasi / Spesifikasi Jabatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_jenjang_pendidikan">Pendidikan Minimum*</label>
                                    <select class="form-select col-sm-12" id="id_jenjang_pendidikan" name="id_jenjang_pendidikan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($jenjang_pendidikan as $data)
                                        <option value="{{ $data->id }}">{{ $data->jenjang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2" id="div_jurusan_pendidikan">
                                    <label class="form-label" for="id_jurusan_pendidikan">Jurusan</label>
                                    <select class="form-select col-sm-12 js-example-basic-single" id="id_jurusan_pendidikan" name="id_jurusan_pendidikan" >
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($jurusan_pendidikan as $data)
                                        <option value="{{ $data->id }}">{{ $data->jurusan }} ({{ $data->_kelompok->kelompok }})</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label" for="nilai_ipk">Nilai IPK</label>
                                    <input type="number" name="nilai_ipk" id="nilai_ipk" class="form-control numbers" placeholder="0.00" step=".01">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="umur_minimum">Usia Minimum (Tahun)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="umur_minimum" id="umur_minimum" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="umur_maksimum">Usia Maksimum (Tahun)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="umur_maksimum" id="umur_maksimum" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_pengalaman">Pengalaman Kerja*</label>
                                    <select class="form-select col-sm-12" id="id_pengalaman" name="id_pengalaman" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($pengalaman_kerja as $data)
                                        <option value="{{ $data->id }}">{{ $data->pengalaman }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <label class="form-label" for="id_pelatihan[]">Pelatihan</label>
                                    <div class="col-md-10 pb-2">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_pelatihan[]" name="id_pelatihan[]" placeholder="Pilih...">
                                            <option selected disabled hidden>Pilih...</option>
                                            @foreach ($pelatihan as $data)
                                            <option value="{{ $data->id }}">{{ $data->sub_pelatihan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pb-2 text-end">
                                        <a class="btn btn-primary" href="{{ route('_pelatihan_master.index') }}" data-toggle="tooltip" title="Add new"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="form-label" for="id_sertifikasi[]">Sertifikasi</label>
                                    <div class="col-md-10 pb-2">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_sertifikasi[]" name="id_sertifikasi[]" >
                                            <option selected disabled hidden>Pilih...</option>
                                            @foreach ($sertifikasi as $data)
                                            <option value="{{ $data->id }}">{{ $data->sub_sertifikasi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pb-2 text-end">
                                        <a class="btn btn-primary" href="{{ route('_sertifikasi_master.index') }}" data-toggle="tooltip" title="Add new"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="form-label" for="id_keterampilan[]">Keterampilan</label>
                                    <div class="col-md-10 pb-2">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_keterampilan[]" name="id_keterampilan[]" >
                                            <option selected disabled hidden>Pilih...</option>
                                            @foreach ($keterampilan as $data)
                                            <option value="{{ $data->id }}">{{ $data->sub_keterampilan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pb-2 text-end">
                                        <a class="btn btn-primary" href="{{ route('_keterampilan_master.index') }}" data-toggle="tooltip" title="Add new"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="form-label" for="id_kepribadian[]">Kepribadian</label>
                                    <div class="col-md-10 pb-2">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_kepribadian[]" name="id_kepribadian[]" >
                                            <option selected disabled hidden>Pilih...</option>
                                            @foreach ($kepribadian as $data)
                                            <option value="{{ $data->id }}">{{ $data->sub_kepribadian }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pb-2 text-end">
                                        <a class="btn btn-primary" href="{{ route('_kepribadian_master.index') }}" data-toggle="tooltip" title="Add new"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal">Tanggal Mulai Kerja*</label>
                                    <input class="form-control" name="tanggal" id="tanggal" type="date" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="keterangan_lain">Keterangan Lain</label>
                                    <textarea class="form-control" id="keterangan_lain" name="keterangan_lain" rows="4" ></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <div class="col-md-12">
                            <div class="card-header">
                                <h5>Line Approval</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="layer1">Layer 1*</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="layer1" name="layer1" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                        </select>
                                        <div class="invalid-feedback txt-danger">Layer 1 is required.</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="layer2">Layer 2*</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="layer2" name="layer2" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                        </select>
                                        <div class="invalid-feedback txt-danger">Layer 2 is required.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="card-body">
                        <!-- <div class="row g-3"> -->
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('kebutuhan.index') }}" class="btn btn-warning" type="button">Back</a>
                            </div>
                        <!-- </div> -->
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
<script type="text/javascript">
// 
$(document).ready(function() {
    // var _url = window.location.origin;
    // default semua hide dan disable=true
    div_waktu_kontrak(0);
    div_kota(0);
    div_jurusan_pendidikan(0);
    div_nik_karyawan(0);
    // 1 = Karyawan Kontrak Alita
    $("#id_status_kerja").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 1 || selectedValue == 9) {
            div_waktu_kontrak(1);
        }else{
            div_waktu_kontrak(0);}
    });
    // 1 = Head Office
    $("#id_area").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue > 1) {
            div_kota(1);
        }else{
            div_kota(0);}
    });
    // 
    $("#id_jenjang_pendidikan").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue > 3 && selectedValue < 11) {
            div_jurusan_pendidikan(1);
        }else{
            div_jurusan_pendidikan(0);}
    });
    // 1 = Penggantian
    $("#id_jenis_permintaan").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 1) {
            div_nik_karyawan(1);
            $("#str_jenis_permintaan").html("penggantian");
        }else{
            div_nik_karyawan(0);
            $("#str_jenis_permintaan").html("penambahan");
        }
    });
    // 
    function div_nik_karyawan(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_nik_karyawan').show();
            $("#nik_karyawan").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_nik_karyawan').hide();
            $("#nik_karyawan").prop('disabled', true);
        }
    }
    // 
    function div_waktu_kontrak(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_waktu_kontrak').show();
            $("#waktu_kontrak").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_waktu_kontrak').hide();
            $("#waktu_kontrak").prop('disabled', true);
        }
    }
    // 
    function div_kota(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_kota').show();
            $("#id_kota").prop('disabled', false);
            $('#id_kota').find('option').remove();
            $("#id_kota").append('<option selected="" disabled="" value="">Pilih...</option>');
            var id = $("#id_area").val();
            $.ajax({
                // url: _url + '/matta/kebutuhan/kota/' + id,
                url: "{{ route('kebutuhan_kota') }}" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    // alert(response);
                    var len = 0;
                    if (response['kota'] != null) {
                        len = response['kota'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['kota'][i].id;
                            var kota = response['kota'][i].kota;
                            var option = "<option value='" + id + "'>" + kota + "</option>";
                            $("#id_kota").append(option);
                        }
                    }
                }
            });
        }
        // jika 0 maka non aktif
        else {
            $('#div_kota').hide();
            $("#id_kota").prop('disabled', true);
        }
    }
    // 
    function div_jurusan_pendidikan(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_jurusan_pendidikan').show();
            $("#id_jurusan_pendidikan").prop('disabled', false);
            $("#nilai_ipk").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_jurusan_pendidikan').hide();
            $("#id_jurusan_pendidikan").prop('disabled', true);
            $("#nilai_ipk").prop('disabled', true);
        }
    }
    // 
    // Empty the dropdown
    $('#layer1').find('option').not(':first').remove();
    $('#layer2').find('option').not(':first').remove();
    // karyawan masih di hardcode
    // Andika Cahya Fajar. R
    // var id = '01031406353';
    // AJAX request 
    // layer1
    $.ajax({
        // url: _url + '/matta/kebutuhan/layer/' + id,
        // url: "{{ route('kebutuhan_layer') }}" + "/" + id,
        url: "{{ route('kebutuhan_layer') }}/{{ auth()->user()->nik }}",
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var layer1 = response['layer'].layer1;
            var len = 0;
            if (response['karyawan'] != null) {
                len = response['karyawan'].length;
            }
            if (len > 0) {
                // Read data and create <option >
                for (var i = 0; i < len; i++) {
        var nik = response['karyawan'][i].nik;
        var nama = response['karyawan'][i].nama;
        if (layer1 == nik) {
            var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
        } else {
            var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
        }
        $("#layer1").append(option);
                }
            }
        }
    });
    // layer2
    $.ajax({
        // url: _url + '/matta/kebutuhan/layer/' + id,
        // url: "{{ route('kebutuhan_layer') }}" + "/" + id,
        url: "{{ route('kebutuhan_layer') }}/{{ auth()->user()->nik }}",
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var layer2 = response['layer'].layer2;
            var len = 0;
            if (response['karyawan'] != null) {
                len = response['karyawan'].length;
            }
            if (len > 0) {
                // Read data and create <option >
                for (var i = 0; i < len; i++) {
        var nik = response['karyawan'][i].nik;
        var nama = response['karyawan'][i].nama;
        if (layer2 == nik) {
            var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
        } else {
            var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
        }
        $("#layer2").append(option);
                }
            }
        }
    });
    // 
});
// 
function cekIsian(){
    // alert($('#jumlah').val());
    // alert($('#nik_karyawan').val());
    if(!document.getElementById('nik_karyawan').disabled){
        var _nik_karyawan = [];
        $("#nik_karyawan :selected").each(function(){
            _nik_karyawan.push($(this).val());
        });
        document.getElementById("_nik_karyawan_").value = _nik_karyawan;
        if($('#jumlah').val() != _nik_karyawan.length){
            alert('Jumlah Karyawan Penggantian harus sesuai dengan isian Jumlah (Orang)');
            return false;
        }else{
            return confirm('Are you sure want to submit this data?');
        }
    }else{
        return confirm('Are you sure want to submit this data?');
    }
}
</script>
@endsection