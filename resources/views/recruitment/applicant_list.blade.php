@extends('layouts.simple.master')

@section('title', 'Recruitment - List of Applicant')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<style>
    /* Applicant List User-Style Page & Table */
    .rec-container {
        padding: 0 4px 30px;
    }
    .dt-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .dt-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 28px;
        border-bottom: 1px solid #f1f5f9;
        gap: 16px;
        flex-wrap: wrap;
        background: #ffffff;
    }
    .dt-title-wrap {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .dt-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #FFF9F1;
        border: 1px solid #fed7aa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff5400;
        flex-shrink: 0;
    }
    .dt-title {
        font-size: 19px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 3px 0;
        line-height: 1.25;
    }
    .dt-desc {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    /* Modern Capsule Tab Switcher */
    .rec-tabs-pill {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 9999px;
        gap: 4px;
    }
    .rec-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 18px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
        text-decoration: none !important;
    }
    .rec-tab-btn:hover {
        color: #ff5400;
    }
    .rec-tab-btn.active, .rec-tab-btn[aria-selected="true"] {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }
    .rec-tab-btn .tab-count {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 9999px;
        background: #e2e8f0;
        color: #475569;
        transition: all .2s;
    }
    .rec-tab-btn.active .tab-count {
        background: #ff5400;
        color: #ffffff;
    }

    /* Filter Box */
    .filter-box {
        background: #fafbfc;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        padding: 20px 24px;
        margin: 20px 28px 8px;
    }
    .filter-box label.h6 {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .filter-scroll-list {
        max-height: 180px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 8px;
        background: #ffffff;
        font-size: 13px;
    }

    /* Action Bar for Bulk Execution */
    .rec-action-bar {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 14px;
        padding: 14px 28px;
        background: #fafbfc;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .btn-bulk-exec {
        background: #ff5400;
        border: none;
        color: #ffffff;
        border-radius: 9999px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(255, 84, 0, 0.28);
        transition: all .18s ease;
    }
    .btn-bulk-exec:hover {
        background: #e04a00;
        box-shadow: 0 4px 12px rgba(255, 84, 0, 0.35);
        color: #ffffff;
    }

    /* Table Container & DataTable styling */
    .table-wrap {
        padding: 16px 28px 24px;
        background: #ffffff;
    }
    #_datatable, #_datatable1, #_datatable2 {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        font-size: 13px !important;
    }
    #_datatable thead th, #_datatable1 thead th, #_datatable2 thead th {
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: .5px !important;
        text-transform: uppercase !important;
        padding: 12px 14px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }
    #_datatable tbody td, #_datatable1 tbody td, #_datatable2 tbody td {
        padding: 11px 14px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: none !important;
        color: #334155 !important;
        white-space: nowrap !important;
    }
    #_datatable tbody tr:hover, #_datatable1 tbody tr:hover, #_datatable2 tbody tr:hover {
        background-color: #fffaf5 !important;
    }

    /* Badges & Elements */
    .id-pill {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
    }
    .badge-rec-status {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 3px 10px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 11.5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .action-icon-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        transition: all .15s ease;
        text-decoration: none !important;
    }
    .action-icon-btn:hover {
        background: #ff5400;
        color: #ffffff;
        border-color: #ff5400;
    }
    .action-icon-btn.btn-view-doc:hover {
        background: #0ea5e9;
        color: #ffffff;
        border-color: #0ea5e9;
    }
    .action-icon-btn.btn-edit:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
    }

    /* Custom DataTables toolbar styling */
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 5px 12px !important;
        font-size: 13px !important;
        background: #ffffff !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        background: #f8fafc !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        background: #ffffff !important;
        border-color: #ff5400 !important;
        outline: none !important;
    }
    .dataTables_wrapper .dt-buttons .btn {
        border-radius: 8px !important;
        font-size: 12.5px !important;
        padding: 6px 12px !important;
        font-weight: 500 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        padding: 5px 11px !important;
        font-size: 12.5px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #ff5400 !important;
        border-color: #ff5400 !important;
        color: #ffffff !important;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>List Of Applicant</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Applicant</li>
@endsection

@section('content')
<div class="container-fluid rec-container">
    @if(Session::has('error'))
    <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="border-radius: 12px;">
        <h6>{{ session('error') }}</h6>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(Session::has('msg'))
    <div class="alert alert-success dark alert-dismissible fade show" role="alert" style="border-radius: 12px;">
        <h6>{{ session('msg') }}</h6>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="dt-card">
        <!-- Header -->
        <div class="dt-head">
            <div class="dt-title-wrap">
                <div class="dt-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <h1 class="dt-title">List of Applicant</h1>
                    <p class="dt-desc">Kelola kandidat pelamar pada tahapan Psychotest, Interview, dan Negotiation</p>
                </div>
            </div>

            <div class="dt-actions">
                <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter" style="border-radius: 9999px; padding: 8px 18px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter Data
                    @if(request('filter'))
                        <span style="background: #ffffff; color: #d97706; border-radius: 9999px; padding: 1px 7px; font-size: 11px; font-weight: 700;">Aktif</span>
                    @endif
                </button>
            </div>
        </div>

        <!-- Filter Collapse -->
        <div class="collapse" id="collapse_filter">
            <div class="filter-box">
                <form action="{{ route('recruitment.applicant_list', 0) }}" method="get" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="h6">Periode Tanggal</label>
                            <input class="form-control mb-2" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="{{ $tanggal_mulai }}" style="border-radius: 8px; font-size: 13px;">
                            <span class="text-muted d-block mb-1" style="font-size: 12px;">sampai</span>
                            <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="{{ $tanggal_akhir }}" style="border-radius: 8px; font-size: 13px;">
                        </div>
                        <div class="col-md-3">
                            <label class="h6" for="nama">Nama Kandidat</label>
                            <select class="form-select js-example-basic-single col-sm-12" id="nama" name="nama" required="">
                                <option value="0" @if(in_array('0', $arr_nama)) selected @endif>Semua</option>
                                @foreach ($nama as $data)
                                <option value="{{ $data->nama }}" @if(in_array($data->nama, $arr_nama)) selected @endif>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="h6 mb-0">Nama Jabatan</label>
                                <span style="font-size: 12px; color: #64748b;"><input type="checkbox" onClick="toggle_id_pekerjaan(this)" /> All</span>
                            </div>
                            <div class="filter-scroll-list">
                                <label class="d-block mb-0" for="id_pekerjaan">
                                    @foreach ($pekerjaan as $data)
                                    <input class="checkbox_animated" id="id_pekerjaan[]" name="id_pekerjaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_pekerjaan)) checked @endif> {{ $data->pekerjaan }}<br/>
                                    @endforeach
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="h6 mb-0">Status</label>
                                <span style="font-size: 12px; color: #64748b;"><input type="checkbox" onClick="toggle_id_status(this)" /> All</span>
                            </div>
                            <div class="filter-scroll-list">
                                <label class="d-block mb-0" for="id_status">
                                    @foreach ($status as $data)
                                    <input class="checkbox_animated" id="id_status[]" name="id_status[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_status)) checked @endif> {{ $data->status }}<br/>
                                    @endforeach
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12 d-flex align-items-center gap-2">
                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit" style="border-radius: 8px; font-weight: 600; font-size: 13px; padding: 7px 18px;"><i class="fa fa-search me-1" aria-hidden="true"></i> Terapkan Filter</button>
                            <a href="{{ route('recruitment.applicant_list', 0) }}" class="btn btn-outline-secondary" type="button" style="border-radius: 8px; font-weight: 600; font-size: 13px; padding: 7px 18px;"><i class="fa fa-refresh me-1" aria-hidden="true"></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modern Capsule Tab Switcher -->
        <div class="px-4 pt-3 pb-2" style="padding: 18px 28px 6px;">
            <div class="rec-tabs-pill" id="pills-applicant-tabs" role="tablist">
                <a class="rec-tab-btn active" id="pills-psychotest-tab" data-bs-toggle="pill" href="#pills-psychotest" role="tab" aria-controls="pills-psychotest" aria-selected="true">
                    <span>Psychotest</span>
                    <span class="tab-count">{{ count($trx) }}</span>
                </a>
                <a class="rec-tab-btn" id="pills-interview-tab" data-bs-toggle="pill" href="#pills-interview" role="tab" aria-controls="pills-interview" aria-selected="false">
                    <span>Interview</span>
                    <span class="tab-count">{{ count($trx1) }}</span>
                </a>
                @can('hc')
                <a class="rec-tab-btn" id="pills-negotiation-tab" data-bs-toggle="pill" href="#pills-negotiation" role="tab" aria-controls="pills-negotiation" aria-selected="false">
                    <span>Negotiation</span>
                    <span class="tab-count">{{ count($trx2) }}</span>
                </a>
                @endcan
            </div>
        </div>

        <div class="tab-content" id="pills-applicant-tabContent">
            {{-- Psychotest Tab --}}
            <div class="tab-pane fade show active" id="pills-psychotest" role="tabpanel" aria-labelledby="pills-psychotest-tab">
                <form action="{{ route('recruitment.applicant_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @csrf
                    <input type="hidden" id="id_status" name="id_status" value="4">
                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">

                    <div class="rec-action-bar">
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0" for="check_in" style="font-size: 13px; font-weight: 600; color: #475569; white-space: nowrap;">Check In Bulk:</label>
                            <input class="form-control" name="check_in" id="check_in" type="datetime-local" required style="border-radius: 8px; font-size: 13px; height: 36px; padding: 4px 10px; width: auto; max-width: 230px;">
                        </div>
                        <button class="btn-bulk-exec" type="submit" name="submit" id="submit" value="psychotest_bulk">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Psychotest Bulk
                        </button>
                    </div>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th><input type="checkbox" onClick="toggle_identitas(this)" /></th>
                                        <th>#ID</th>
                                        <th>Action</th>
                                        <th>Hasil</th>
                                        <th>Upload Hasil</th>
                                        <th>Area Kerja</th>
                                        <th>Area Kerja Kota</th>
                                        <th>Pengaju</th>
                                        <th>NIP</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Nama Jabatan</th>
                                        <th>Atasan Langsung Kandidat</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th><div class="d-flex align-items-center"><input type="checkbox" onClick="toggle_cv(this)" />&nbsp; CV &nbsp;<a href="#" onClick="download_cv()" data-toggle="tooltip" title="Download"><i class="fa fa-file-pdf-o"></i></a></div></th>
                                        <th>Check_in</th>
                                        <th>Check_out</th>
                                        <th>Interpretasi</th>
                                        <th>Offering Letter</th>
                                        <th>Upload</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=0; @endphp
                                    @foreach ($trx as $data)
                                    @php
                                        $kebutuhan_atribute = is_array($data->kebutuhan_atribute) ? $data->kebutuhan_atribute : (json_decode($data->kebutuhan_atribute, true) ?? []);
                                        $atasan_prop_raw = $kebutuhan_atribute['atasan_property'] ?? null;
                                        $kebutuhan_atribute_atasan_property = is_array($atasan_prop_raw) ? $atasan_prop_raw : (json_decode($atasan_prop_raw, true) ?? []);
                                        $recruitment = App\Models\TrxKebutuhanMaster::find($data->id_kebutuhan);
                                        $pengaju_property = $recruitment ? (is_array($recruitment->pengaju_property) ? $recruitment->pengaju_property : (json_decode($recruitment->pengaju_property, true) ?? [])) : [];
                                        $atasan_property = $recruitment ? (is_array($recruitment->atasan_property) ? $recruitment->atasan_property : (json_decode($recruitment->atasan_property, true) ?? [])) : [];
                                        $nomor = $data->id_kebutuhan.'/'.$data->id;
                                        $where = ['id_identitas' => $data->id, 'jenis' => 4];
                                        $pohon = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                        $where = ['id_identitas' => $data->id, 'jenis' => 5];
                                        $manusia = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                        $check1 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->exists();
                                        $check2 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                        $check = $check1 && !$check2;
                                        $check_tiu51 = App\Models\TrxRecruitmentTiu5::where(['id_identitas' => $data->id])->exists();
                                        $check_tiu52 = App\Models\TrxRecruitmentTiu5::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                        $check_tiu5 = $check_tiu51 && !$check_tiu52;
                                        $log = App\Models\TrxRecruitmentLog::where(['id_identitas'=>$data->id])->pluck('id_status')->toArray();
                                        $check3 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id, 'jenis' => 1, 'nomor' => 1])->whereNull('check_out')->exists();
                                        $n_log = App\Models\TrxRecruitmentPsychotestLog::selectRaw('COUNT(*) as total')->where(['id_identitas'=>$data->id])->groupBy('urutan')->get()->count();
                                    @endphp
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>@if($data->id_status == 3) <input id="id_identitas[]" name="id_identitas[]" type="checkbox" value="{{ $data->id }}"> @endif</td>
                                        <td><span class="id-pill">{{ $nomor }}</span></td>
                                        <td>@can('hc')
                                            <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Process' class="action-icon-btn"><i class="icon-pencil-alt"></i></a>
                                            @if(!in_array(5, $log) && $data->id_status > 3)&nbsp;<a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'type'=>'extend']) }}" data-toggle="tooltip" title='Extend' class="action-icon-btn"><i class="fa fa-calendar-plus-o"></i></a>@endif
                                            <a href="{{ route('recruitment.applicant_item', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Form' target="_blank" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                            <a href="{{ route('recruitment.psychotest', [Crypt::encrypt(array($data->id, 'psychotest'))]) }}" data-toggle="tooltip" title='Psychotest' target="_blank" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                        @endcan</td>
                                        <td>
                                            @if($check_tiu5)
                                            <a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,6])]) }}" target="_blank" data-toggle="tooltip" title="TIU5"><i class="fa fa-file-text-o"></i></a><br/>
                                            @endif
                                            @if($check)
                                            <a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,1])]) }}" target="_blank" data-toggle="tooltip" title="EPPS"><i class="fa fa-file-text-o"></i></a>
                                            &nbsp;<a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,2])]) }}" target="_blank" data-toggle="tooltip" title="PAPI Kostick"><i class="fa fa-file-text-o"></i></a>
                                            &nbsp;<a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,3])]) }}" target="_blank" data-toggle="tooltip" title="DISC"><i class="fa fa-file-text-o"></i></a>
                                            @if(!empty($pohon[0]->jawaban1))<br/><a href="{{ asset('assets/recruitment/upload/'.$pohon[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="BAUM"><i class="fa fa-file-image-o"></i></a>@endif
                                            @if(!empty($manusia[0]->jawaban1))&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$manusia[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="DAP"><i class="fa fa-file-image-o"></i></a>@endif
                                            &nbsp;<a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'process'=>'psikotest_revisi']) }}" target="_self" data-toggle="tooltip" title='Revisi'><i class="icon-pencil-alt"></i></a>
                                            @endif
                                            @if($n_log > 0)<br/><a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'process'=>'histori_revisi']) }}" target="_self" data-toggle="tooltip" title='Histori Revisi'><i class="fa fa-history"></i></a>@endif
                                        </td>
                                        <td>
                                            @if($check)
                                            @if(!empty($data->file_epps))<a href="{{ asset('assets/recruitment/upload/'.$data->file_epps) }}" target="_blank" data-toggle="tooltip" title="Download EPPS"><i class="fa fa-file-text-o" style="color: orange;"></i></a>@else<i class="fa fa-file-text-o" style="opacity: 0;"></i>@endif
                                            &nbsp;@if(!empty($data->file_papi))<a href="{{ asset('assets/recruitment/upload/'.$data->file_papi) }}" target="_blank" data-toggle="tooltip" title="Download PAPI Kostick"><i class="fa fa-file-text-o" style="color: orange;"></i></a>@else<i class="fa fa-file-text-o" style="opacity: 0;"></i>@endif
                                            &nbsp;@if(!empty($data->file_disc))<a href="{{ asset('assets/recruitment/upload/'.$data->file_disc) }}" target="_blank" data-toggle="tooltip" title="Download DISC"><i class="fa fa-file-text-o" style="color: orange;"></i></a>@else<i class="fa fa-file-text-o" style="opacity: 0;"></i>@endif
                                            <br/>@if(!empty($data->file_baum))<a href="{{ asset('assets/recruitment/upload/'.$data->file_baum) }}" target="_blank" data-toggle="tooltip" title="Download BAUM"><i class="fa fa-file-image-o" style="color: orange;"></i></a>@else<i class="fa fa-file-image-o" style="opacity: 0;"></i>@endif
                                            &nbsp;@if(!empty($data->file_dap))<a href="{{ asset('assets/recruitment/upload/'.$data->file_dap) }}" target="_blank" data-toggle="tooltip" title="Download DAP"><i class="fa fa-file-image-o" style="color: orange;"></i></a>@else<i class="fa fa-file-image-o" style="opacity: 0;"></i>@endif
                                            &nbsp;<a href="{{ route('recruitment.upload_psikotest', [Crypt::encrypt([$data->id, 'upload'])]) }}" target="_blank" data-toggle="tooltip" title='Upload'><i class="fa fa-upload" style="color: orange;"></i></a>
                                            @endif
                                        </td>
                                        <td>{{ !empty($kebutuhan_atribute['id_area']) ? (App\Models\Propinsi::find($kebutuhan_atribute['id_area'])?->propinsi ?? '-') : '-' }}</td>
                                        <td>@if(!empty($kebutuhan_atribute['id_kota']) && App\Models\PropinsiKota::find($kebutuhan_atribute['id_kota'])?->kota !== null) {{ App\Models\PropinsiKota::find($kebutuhan_atribute['id_kota'])->kota }} @else - @endif</td>
                                        <td>{{ $pengaju_property['nama'] ?? '-' }}</td>
                                        <td>{{ $pengaju_property['nik'] ?? '-' }}</td>
                                        <td>{{ $pengaju_property['divisi'] ?? '-' }}</td>
                                        <td>{{ $pengaju_property['dep'] ?? '-' }}</td>
                                        <td>{{ $pengaju_property['sub_dep'] ?? '-' }}</td>
                                        <td>{{ !empty($kebutuhan_atribute['id_pekerjaan']) ? (App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])?->pekerjaan ?? '-') : ($recruitment?->_pekerjaan?->pekerjaan ?? '-') }}</td>
                                        <td>{{ ($kebutuhan_atribute_atasan_property['nama'] ?? ($atasan_property['nama'] ?? '-')) }}/{{ ($kebutuhan_atribute_atasan_property['nik'] ?? ($atasan_property['nik'] ?? '-')) }}</td>
                                        <td><strong>{{ $data->nama }}</strong></td>
                                        <td>{{ $data->email }}&nbsp;<a href="{{ route('recruitment.edit', [Crypt::encrypt($data->id), 'type'=>'email', 'back_url'=>URL::full()]) }}" data-toggle="tooltip" title='Edit' class="action-icon-btn btn-edit"><i class="icon-pencil-alt" style="color:green"></i></a></td>
                                        <td>{{ $data->telepon }}</td>
                                        <td><div class="d-flex align-items-center gap-1"><input id="id_cv[]" name="id_cv[]" type="checkbox" value="{{ $data->id }}">&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$data->cv) }}" target="_blank" data-toggle="tooltip" title="File CV" class="action-icon-btn btn-view-doc"><i class="fa fa-file-pdf-o"></i></a></div></td>
                                        <td>@if(!empty($data->check_in)){{ \Carbon\Carbon::parse($data->check_in) ->format('d-m-Y H:i:s') }} @if($check3) <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'process'=>'psikotest_reschedule']) }}" target="_self" data-toggle="tooltip" title="Reschedule"><i class="fa fa-calendar" aria-hidden="true"></i></a> @endif @endif</td>
                                        <td>@if(!empty($data->check_out)){{ \Carbon\Carbon::parse($data->check_out) ->format('d-m-Y H:i:s') }}@endif</td>
                                        <td>@can('hc')
                                            @if(in_array(16, $log))<a href="{{ route('recruitment.get_interpretation', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="Interpretation" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o"></i></a>@endif
                                        @endcan</td>
                                        <td>@can('hc')
                                            @if(in_array(12, $log))<a href="{{ route('recruitment.get_offering', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="Offering Letter" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o"></i></a>@endif
                                        @endcan</td>
                                        <td>@can('hc')
                                            @if(in_array(20, $log))<a href="{{ route('recruitment.upload_dokumen_get', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="File Upload" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o"></i></a>@endif
                                        @endcan</td>
                                        <td><span class="badge-rec-status">{{ $data->_status?->status ?? '-' }}</span></td>
                                        <td>@if(!empty($data->id_join)) {!! implode('<br/>', App\Models\MtrJoin::whereIn('id', explode(',', $data->id_join))->orderBy('nama', 'asc')->pluck('nama')->toArray()) !!} @endif</td>
                                        <td><span class="text-muted" style="font-size: 12px;">{{ $data->created_at }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Interview Tab --}}
            <div class="tab-pane fade" id="pills-interview" role="tabpanel" aria-labelledby="pills-interview-tab">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="display" id="_datatable1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Tipe</th>
                                    <th>Nama</th>
                                    <th>Pewawancara</th>
                                    <th>Metode</th>
                                    <th>Check_in</th>
                                    <th>Check_out</th>
                                    <th>Interview</th>
                                    <th>Status</th>
                                    <th>Created_at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($trx1 as $data)
                                @php
                                    $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                    $nomor = $identitas ? ($identitas->id_kebutuhan . '/' . $identitas->id) : ($data->id_identitas ?? '-');
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td><span class="id-pill">{{ $nomor }}</span></td>
                                    <td><a href="{{ route('recruitment.interview_item', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Process' class="action-icon-btn"><i class="icon-pencil-alt"></i></a></td>
                                    <td class="text-uppercase"><span class="badge-rec-status">{{ $data->jenis }}</span></td>
                                    <td><strong>{{ $identitas?->nama ?? '-' }}</strong></td>
                                    <td>
                                        @if(!empty($data->interviewer))
                                        @foreach(explode(';', $data->interviewer) as $_data)
                                            @php
                                            $karyawan = App\Models\Karyawan::where(['nik'=>$_data])->first();
                                            @endphp
                                            {{ $karyawan?->nama ?? '-' }}/{{ $_data }}<br/>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $data->metode }}</td>
                                    <td>@if(!empty($data->check_in)){{ \Carbon\Carbon::parse($data->check_in) ->format('d-m-Y H:i:s') }} <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id_identitas), 'process'=>'interview_'.$data->jenis.'_reschedule', 'id_interview'=>Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title="Reschedule"><i class="fa fa-calendar" aria-hidden="true"></i></a> @endif</td>
                                    <td>@if(!empty($data->check_out)){{ \Carbon\Carbon::parse($data->check_out) ->format('d-m-Y H:i:s') }} @endif</td>
                                    <td>@if(!empty($data->file_interview))<a href="{{ asset('assets/recruitment/upload/'.$data->file_interview) }}" target="_blank" data-toggle="tooltip" title="File Interview" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o"></i></a> @endif</td>
                                    <td><span class="badge-rec-status">{{ $data->_status?->status ?? '-' }}</span></td>
                                    <td><span class="text-muted" style="font-size: 12px;">{{ $data->created_at }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Negotiation Tab --}}
            @can('hc')
            <div class="tab-pane fade" id="pills-negotiation" role="tabpanel" aria-labelledby="pills-negotiation-tab">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="display" id="_datatable2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>PIC</th>
                                    <th>THP</th>
                                    <th>Benefit</th>
                                    <th>Mulai Bergabung</th>
                                    <th>Pencatatan Kepegawaian</th>
                                    <th>Status Kepegawaian</th>
                                    <th>Masa Perjanjian Kerja</th>
                                    <th>Catatan</th>
                                    <th>Lampiran</th>
                                    <th>Status</th>
                                    <th>Created_at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($trx2 as $data)
                                @php
                                    $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                    $nomor = $identitas ? ($identitas->id_kebutuhan . '/' . $identitas->id) : ($data->id_identitas ?? '-');
                                    $benefit_utama = !empty($data->benefit_utama) ? App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $data->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray() : [];
                                    $benefit_utama = implode('<br/>', $benefit_utama);
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td><span class="id-pill">{{ $nomor }}</span></td>
                                    <td>{{ $data->pic }}</td>
                                    <td><strong>Rp. {{ number_format($data->thp ?? 0, 2) }}</strong></td>
                                    <td>{!! $benefit_utama !!}
                                        @if(!empty($data->benefit_lainnya))<br/>{{ $data->benefit_lainnya }}@endif
                                    </td>
                                    <td>{{ !empty($data->tgl_bergabung) ? Carbon\Carbon::parse($data->tgl_bergabung)->format('d-m-Y') : '-' }}</td>
                                    <td><span class="badge-rec-status">{{ $data->_catat_pegawai?->pencatatan ?? '-' }}</span></td>
                                    <td><span class="badge-rec-status">{{ $data->_status_pegawai?->status ?? '-' }}</span></td>
                                    <td>{{ $data->masa_kerja }} bulan</td>
                                    <td>{{ $data->catatan }}</td>
                                    <td>@if(!empty($data->lampiran))<a href="{{ asset('assets/recruitment/upload/'.$data->lampiran) }}" target="_blank" data-toggle="tooltip" title="File Lampiran" class="action-icon-btn btn-view-doc"><i class="fa fa-file-text-o"></i></a>@endif</td>
                                    <td><span class="badge-rec-status">{{ $data->_status?->status ?? '-' }}</span></td>
                                    <td><span class="text-muted" style="font-size: 12px;">{{ $data->created_at }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endcan
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
<script type="text/javascript">
function toggle_identitas(source) {
    var checkboxes = document.getElementsByName('id_identitas[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

function toggle_cv(source) {
    var checkboxes = document.getElementsByName('id_cv[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

var arr_id_cv = [];
function download_cv() {
    $('input[name="id_cv[]"]:checked').each(function() {
        arr_id_cv.push($(this).val());
    });
    if(arr_id_cv.length > 0){
        window.location.href = "{{ route('recruitment.download_cv', 0) }}"+"?download_cv="+arr_id_cv;
    }else{
        alert('No CV is selected!');
    }
    arr_id_cv = [];
}

function toggle_nama(source) {
    var checkboxes = document.getElementsByName('nama[]');
    for(var i=0, n=checkboxes.length; i<n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_pekerjaan(source) {
    var checkboxes = document.getElementsByName('id_pekerjaan[]');
    for(var i=0, n=checkboxes.length; i<n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_status(source) {
    var checkboxes = document.getElementsByName('id_status[]');
    for(var i=0, n=checkboxes.length; i<n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

const recDtOptions = {
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 text-md-center'B><'col-sm-12 col-md-4 text-md-end'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons: [
        { extend: 'copy', text: '<i class="fa fa-clone"></i> Copy', className: 'btn btn-outline-secondary' },
        { extend: 'csv', text: '<i class="fa fa-file-text-o"></i> CSV', className: 'btn btn-outline-secondary' },
        { extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-outline-secondary' },
        { extend: 'pdf', text: '<i class="fa fa-file-pdf-o"></i> PDF', className: 'btn btn-outline-secondary' },
        { extend: 'print', text: '<i class="fa fa-print"></i> Print', className: 'btn btn-outline-secondary' }
    ],
    order: [],
    autoWidth: false,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Cari pelamar...",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        paginate: {
            previous: "<i class='fa fa-angle-left'></i>",
            next: "<i class='fa fa-angle-right'></i>"
        }
    }
};

$('#_datatable').DataTable(recDtOptions);
$('#_datatable1').DataTable(recDtOptions);
$('#_datatable2').DataTable(recDtOptions);

$('button[data-bs-toggle="pill"], a[data-bs-toggle="pill"]').on('shown.bs.tab', function () {
    $($.fn.dataTable.tables({ visible: true, api: true })).DataTable().columns.adjust();
});
</script>
@endsection
