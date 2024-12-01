@extends('layouts.simple.master')

@section('title', 'Recruitment')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
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
<h3>Process</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Process</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Process</h5>
                </div>
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload file is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label for="id" class="form-label">#ID</label>
                                <input type="text" class="form-control" name="id" id="id" placeholder="ID" value="{{ $trx->id_kebutuhan }}/{{ $trx->id }}" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label for="nama" class="form-label">Nama/Email Kandidat</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama/Email" value="{{ $trx->nama }}/{{ $trx->email }}" disabled>
                            </div>
                        </div>
                    </div>
                    {{-- start --}}
                    @if(in_array($trx->id_status, [17,18]))
                        @if(!empty($nego))
                        @php
                        // 17=Negotiation Deal by Candidate
                        // 18=Negotiation No Deal by Candidate
                        $nego_log = App\Models\TrxRecruitmentNegosiasiLog::where(['id_identitas'=>$nego->id_identitas])->whereIn('id_status', [17,18])->orderBy('id', 'desc')->first();
                        @endphp
                        @if(!empty($nego_log))
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">ORGANISASI</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="posisi">Posisi / Jabatan</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->posisi }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="area_kerja">Area Kerja</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->area_kerja }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="divisi">Direktorat / Divisi / Departemen</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->divisi }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">BENEFIT</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="thp">Take Home Pay (THP)</label>
                                    <input class="form-control" type="text" readonly="" value="Rp. {{ number_format($nego_log->thp, 0) }}">
                                </div>
                            </div>
                            @php
                            $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $nego_log->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                            $benefit_utama = implode('&#13;&#10;', $benefit_utama);
                            @endphp
                            <div class="col-12 col-md-4">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="benefit_utama">Benefit</label>
                                    <textarea class="form-control" rows="5" readonly="">{!! $benefit_utama !!}</textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->benefit_lainnya }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">KEPEGAWAIAN</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung</label>
                                    <input class="form-control" type="text" readonly="" value="{{ Carbon\Carbon::parse($nego_log->tgl_bergabung)->format('d-m-Y') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->_catat_pegawai->pencatatan }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="id_status_pegawai">Status Kepegawaian</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->_status_pegawai->status }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)</label>
                                    <input class="form-control" type="text" readonly="" value="{{ $nego_log->masa_kerja }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">CATATAN DAN LAMPIRAN</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="catatan">Catatan</label>
                                    <textarea class="form-control" rows="4" readonly="">{{ $nego_log->catatan }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="col-12 mb-2">
                                    @if(!empty($nego_log->lampiran))Download Lampiran&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$nego_log->lampiran) }}" target="_blank" data-toggle="tooltip" title="File Lampiran"><i class="fa fa-file-text-o"></i></a>@endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">RESPON</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="col-12 col-md-12 mb-2">
                            <input class="form-control" name="lbl_id_status" id="lbl_id_status" type="text" readonly="" value="{{ $nego_log->_status->status }}">
                        </div>
                        @endif
                        @endif
                    @endif
                    {{-- end --}}
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="id_process">Process</label>
                                <select class="form-select col-sm-12" id="id_process" name="id_process" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($id_status as $data)
                                    <option value="{{ $data->id }}">{{ $data->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="{{ route('recruitment.applicant_list', 0) }}" class="btn btn-warning" type="button">Back</a>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    {{-- 4=Psycotest Send --}}
                    <div id="row_4">
                        <form action="{{ route('recruitment.applicant_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="4">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_in">Check_in</label>
                                    <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_out">Check_out</label>
                                    <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 6=Interview User and HCO Send --}}
                    <div id="row_6">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="6">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                            @foreach ($karyawan_user_hco as $data)
                                            <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 8=Interview User Send --}}
                    <div id="row_8">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="8">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                            @foreach ($karyawan as $data)
                                            <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 10=Interview HCO Send --}}
                    <div id="row_10">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="10">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                            @foreach ($karyawan_hco as $data)
                                            <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 16=Interpretation --}}
                    <div id="row_16">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="16">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            @if(!empty($resume))
                            <h6 class="text-start">Rekomendasi dan Kesimpulan</h6>
                            <div class="row">
                                {{-- Rekomendasi --}}
                                @php $x=0 @endphp
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_{{ $resume[$x]->id }}">{{ $resume[$x]->pernyataan1 }}</label>
                                        <select class="form-select col-sm-12" id="jawaban_{{ $resume[$x]->id }}" name="jawaban_{{ $resume[$x]->id }}" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="1">Disarankan</option>
                                            <option value="0">Tidak Disarankan</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Kesimpulan --}}
                                @php $x=3 @endphp
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_{{ $resume[$x]->id }}">{{ $resume[$x]->pernyataan1 }}</label>
                                        <textarea class="form-control" id="jawaban_{{ $resume[$x]->id }}" name="jawaban_{{ $resume[$x]->id }}" rows="4" required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <h6 class="text-start">Uraian Perilaku</h6>
                            <div class="row">
                                @php $x=1 @endphp
                                {{-- Strong Point --}}
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_{{ $resume[$x]->id }}">{{ $resume[$x]->pernyataan1 }}</label>
                                        <textarea class="form-control" id="jawaban_{{ $resume[$x]->id }}" name="jawaban_{{ $resume[$x]->id }}" rows="4" required=""></textarea>
                                    </div>
                                </div>
                                @php $x=2 @endphp
                                {{-- To Be Develop --}}
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_{{ $resume[$x]->id }}">{{ $resume[$x]->pernyataan1 }}</label>
                                        <textarea class="form-control" id="jawaban_{{ $resume[$x]->id }}" name="jawaban_{{ $resume[$x]->id }}" rows="4" required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            @php $x=4 @endphp
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-start">
                                            <tr>
                                                <th>Core Competence</th>
                                                <th>Standard Competence</th>
                                                <th>Poin (10-100)</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($x = 4; $x < 14; $x++)
                                            <tr>
                                                <td>@if($resume[$x]->urutan == 1){{ $resume[$x]->pernyataan1 }}@endif</td>
                                                <td>{{ $resume[$x]->pernyataan2 }}</td>
                                                <td><input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="jawaban_{{ $resume[$x]->id }}" id="jawaban_{{ $resume[$x]->id }}" placeholder="Poin" required=""></td>
                                                <td><input class="form-control" name="keterangan_{{ $resume[$x]->id }}" id="keterangan_{{ $resume[$x]->id }}" type="text" placeholder="Keterangan"></td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <button class="btn btn-primary" type="submit">Submit</button>
                            {{-- <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interpretasi">Interpretation File</label>
                                        <input name="interpretasi" id="interpretasi" class="form-control" type="file" required="">
                                        <label class="form-label">*Maksimal 20 MB (any format)</label>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div> --}}
                            @endif
                        </form>
                    </div>
                    {{-- 12=Negotiation Process --}}
                    <div id="row_12">
                        <form action="{{ route('recruitment.negotiation_send') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="12">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                @php
                                $pengaju = json_decode($trxM->pengaju_property, true);
                                @endphp
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">ORGANISASI</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="posisi">Posisi / Jabatan</label>
                                        <input class="form-control" name="posisi" id="posisi" type="text" readonly="" value="{{ $trxM->_pekerjaan->pekerjaan }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="area_kerja">Area Kerja</label>
                                        <input class="form-control" name="area_kerja" id="area_kerja" type="text" readonly="" value="{{ $trxM->_area->propinsi }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="divisi">Direktorat / Divisi / Departemen</label>
                                        <input class="form-control" name="divisi" id="divisi" type="text" readonly="" value="{{ $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">BENEFIT</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="thp">Take Home Pay (THP)<span class="text-danger">*</span></label>
                                        <input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="thp" id="thp" placeholder="Take Home Pay (THP)" required="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                        <input class="form-control" name="benefit_lainnya" id="benefit_lainnya" type="text" placeholder="Benefit lainnya">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="benefit_utama">Benefit<span class="text-danger">*</span></label><br/>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit_utama[]" name="benefit_utama[]" required="">
                                            @foreach ($benefit as $data)
                                            <option value="{{ $data->id }}">{{ $data->benefit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">KEPEGAWAIAN</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_bergabung" id="tgl_bergabung" placeholder="Tanggal Mulai Bergabung" required="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_catat_pegawai" name="id_catat_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach ($catat_pegawai as $data)
                                            <option value="{{ $data->id }}">{{ $data->pencatatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_status_pegawai">Status Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_status_pegawai" name="id_status_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach ($status_pegawai as $data)
                                            <option value="{{ $data->id }}">{{ $data->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="masa_kerja" id="masa_kerja" placeholder="Masa Perjanjian Kerja (bulan)" required="">
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan HCO</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran HCO</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 13=Negotiation Deal by HCO --}}
                    <div id="row_13">
                        @if(!empty($nego))
                        <form action="{{ route('recruitment.negotiation_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($nego->id) }}">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="{{ Crypt::encrypt($nego->id_identitas) }}">
                            <input type="hidden" id="id_status" name="id_status" value="13">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_dokumen_ttd">Pedoman Perilaku Alita</label>
                                        <select class="form-select col-sm-12" id="file_dokumen_ttd" name="file_dokumen_ttd" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="leader.pdf">Pedoman Perilaku Alita (Leader)</option>
                                            <option value="general.pdf">Pedoman Perilaku Alita (General)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan HCO</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran HCO</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    {{-- 14=Negotiation No Deal by HCO --}}
                    <div id="row_14">
                        @if(!empty($nego))
                        <form action="{{ route('recruitment.negotiation_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($nego->id) }}">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="{{ Crypt::encrypt($nego->id_identitas) }}">
                            <input type="hidden" id="id_status" name="id_status" value="14">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan HCO</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran HCO</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    {{-- 21=Re-Negotiation Process --}}
                    <div id="row_21">
                        @if(!empty($nego))
                        {{-- form edit Re-Negotiation Process --}}
                        <form action="{{ route('recruitment.negotiation_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($nego->id) }}">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="{{ Crypt::encrypt($nego->id_identitas) }}">
                            <input type="hidden" id="id_status" name="id_status" value="21">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">BENEFIT</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="thp">Take Home Pay (THP)<span class="text-danger">*</span></label>
                                        <input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="thp" id="thp" placeholder="Take Home Pay (THP)" required="" value="{{ $nego->thp }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                        <input class="form-control" name="benefit_lainnya" id="benefit_lainnya" type="text" placeholder="Benefit lainnya" value="{{ $nego->benefit_lainnya }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="benefit_utama">Benefit<span class="text-danger">*</span></label><br/>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit_utama[]" name="benefit_utama[]" required="">
                                            @foreach ($benefit as $data)
                                            <option value="{{ $data->id }}" @if(in_array($data->id, explode(',', $nego->benefit_utama))) selected @endif>{{ $data->benefit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">KEPEGAWAIAN</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_bergabung" id="tgl_bergabung" placeholder="Tanggal Mulai Bergabung" required="" value="{{ $nego->tgl_bergabung }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_catat_pegawai" name="id_catat_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach ($catat_pegawai as $data)
                                            <option value="{{ $data->id }}" @if($data->id == $nego->id_catat_pegawai) selected @endif>{{ $data->pencatatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_status_pegawai">Status Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_status_pegawai" name="id_status_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach ($status_pegawai as $data)
                                            <option value="{{ $data->id }}" @if($data->id == $nego->id_status_pegawai) selected @endif>{{ $data->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="masa_kerja" id="masa_kerja" placeholder="Masa Perjanjian Kerja (bulan)" required="" value="{{ $nego->masa_kerja }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan HCO</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran HCO</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    {{-- 15=Recruitment Done --}}
                    <div id="row_15">
                        @if(!empty($nego))
                        <form action="{{ route('recruitment.negotiation_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($nego->id) }}">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="{{ Crypt::encrypt($nego->id_identitas) }}">
                            <input type="hidden" id="id_status" name="id_status" value="15">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    {{-- 22=Psychotest and Interview Pass --}}
                    <div id="row_22">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="22">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 23=Psychotest and Interview Not Pass --}}
                    <div id="row_23">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="23">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 24=Join --}}
                    <div id="row_24">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="24">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan HCO</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran HCO</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- 25=Join --}}
                    <div id="row_25">
                        <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                            <input type="hidden" id="id_status" name="id_status" value="25">
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan HCO</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran HCO</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{--  --}}
                </div>
            </div>
        </div>
        {{--  --}}
        <div class="col-12 col-md-4 notification">
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($history as $_history)
                        <li class="d-flex">
                            @if ($loop->first)
                            <div class="activity-dot-success"></div>
                            @elseif ($loop->last)
                            <div class="activity-dot-warning"></div>
                            @else
                            <div class="activity-dot-primary"></div>
                            @endif
                            <div class="ms-3">
                                <p class="d-flex justify-content-between mb-2"><span class="date-content light-background">{{ $_history->created_at }}</span></p>
                                <h6>{{ $_history->_status->status }}<span class="dot-notification"></span></h6>
                                <p class="f-light">{{ $_history->pic }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
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
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
    function use_number(node) {
      var empty_val = false;
      const value = node.value;
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
    $(document).ready(function () {
        // 4=Psycotest Send
        // 6=Interview User and HCO Send
        // 8=Interview User Send
        // 10=Interview HCO Send
        // 16=Interpretation
        // 12=Negotiation Process
        // 13=Negotiation Deal by HCO
        // 14=Negotiation No Deal by HCO
        // 15=Recruitment Done
        // 21=Re-Negotiation Process
        // 22=Psychotest and Interview Pass
        // 23=Psychotest and Interview Not Pass
        // 24=Join
        // 25=Not Join
        $("#row_4").hide();
        $("#row_6").hide();
        $("#row_8").hide();
        $("#row_10").hide();
        $("#row_16").hide();
        $("#row_12").hide();
        $("#row_13").hide();
        $("#row_14").hide();
        $("#row_15").hide();
        $("#row_21").hide();
        $("#row_22").hide();
        $("#row_23").hide();
        $("#row_24").hide();
        $("#row_25").hide();
        // 
        $("#id_process").change(function () {
            $value = $("#id_process").val();
            // 4=Psychotest Send
            if ($value == 4) {
                $("#row_4").show();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 6=Interview User and HCO Send
            else if ($value == 6) {
                $("#row_4").hide();
                $("#row_6").show();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 8=Interview User Send
            else if ($value == 8) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").show();
                $("#row_16").hide();
                $("#row_10").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 10=Interview HCO Send
            else if ($value == 10) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").show();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 16=Interpretation
            else if ($value == 16) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").show();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 12=Negotiation Process
            else if ($value == 12) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").show();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 13=Negotiation Deal by HCO
            else if ($value == 13) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").show();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 14=Negotiation No Deal by HCO
            else if ($value == 14) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").show();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 21=Re-Negotiation Process
            else if ($value == 21) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").show();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 22=Psychotest and Interview Pass
            else if ($value == 22) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").show();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 23=Psychotest and Interview Not Pass
            else if ($value == 23) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").show();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // 24=Join
            else if ($value == 24) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").show();
                $("#row_25").hide();
            }
            // 25=Not Join
            else if ($value == 25) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").show();
            }
            // 15=Recruitment Done
            else if ($value == 15) {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").show();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
            // lainnya
            else {
                $("#row_4").hide();
                $("#row_6").hide();
                $("#row_8").hide();
                $("#row_10").hide();
                $("#row_16").hide();
                $("#row_12").hide();
                $("#row_13").hide();
                $("#row_14").hide();
                $("#row_15").hide();
                $("#row_21").hide();
                $("#row_22").hide();
                $("#row_23").hide();
                $("#row_24").hide();
                $("#row_25").hide();
            }
        });
    });
</script>
@endsection