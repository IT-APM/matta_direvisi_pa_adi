@extends('layouts.simple.master')

@php
    $filename = request('filename');
    $moduleMap = [
        'emails.lembur0' => [
            'name' => 'Lembur',
            'sub' => 'Daftar pengajuan lembur karyawan yang memerlukan persetujuan dan tindakan Anda',
            'icon' => 'clock'
        ],
        'emails.penilaian0' => [
            'name' => 'Evaluasi',
            'sub' => 'Daftar formulir evaluasi penilaian karyawan yang memerlukan tindakan Anda',
            'icon' => 'check-square'
        ],
        'emails.penilaian0_outsourcing' => [
            'name' => 'Evaluasi Outsourcing',
            'sub' => 'Daftar evaluasi penilaian karyawan outsourcing yang memerlukan tindakan Anda',
            'icon' => 'check-square'
        ],
        'emails.cuti0' => [
            'name' => 'Cuti',
            'sub' => 'Daftar permohonan cuti karyawan yang memerlukan persetujuan Anda',
            'icon' => 'calendar'
        ],
        'emails.izin0' => [
            'name' => 'Izin',
            'sub' => 'Daftar pengajuan izin kerja karyawan yang memerlukan persetujuan Anda',
            'icon' => 'file-text'
        ],
        'emails.nonaktif0' => [
            'name' => 'Izin Keluar',
            'sub' => 'Daftar pengajuan exit clearance / izin keluar karyawan yang memerlukan tindakan Anda',
            'icon' => 'log-out'
        ],
        'emails.reimburse0' => [
            'name' => 'Pengembalian Biaya',
            'sub' => 'Daftar klaim reimbursement / pengembalian biaya yang memerlukan verifikasi Anda',
            'icon' => 'credit-card'
        ],
        'emails.sakit0' => [
            'name' => 'Sakit',
            'sub' => 'Daftar pengajuan surat keterangan sakit karyawan yang memerlukan tindakan Anda',
            'icon' => 'heart'
        ],
        'emails.karyawan_bulanan0' => [
            'name' => 'Konfirmasi Karyawan',
            'sub' => 'Daftar konfirmasi status karyawan bulanan yang memerlukan persetujuan Anda',
            'icon' => 'clipboard-check'
        ],
        'emails.rekomendasi0' => [
            'name' => 'Rekomendasi',
            'sub' => 'Daftar rekomendasi karyawan yang memerlukan evaluasi dan persetujuan Anda',
            'icon' => 'award'
        ],
        'emails.kebutuhan0' => [
            'name' => 'Tambah Karyawan',
            'sub' => 'Daftar pengajuan kebutuhan penambahan tenaga kerja / karyawan baru',
            'icon' => 'user-plus'
        ],
        'emails.pelatihan0' => [
            'name' => 'Pelatihan',
            'sub' => 'Daftar pengajuan pelatihan / training karyawan yang memerlukan persetujuan Anda',
            'icon' => 'book-open'
        ],
        'emails.bpd0' => [
            'name' => 'Perjalanan Dinas',
            'sub' => 'Daftar pengajuan biaya perjalanan dinas (BPD) yang memerlukan persetujuan Anda',
            'icon' => 'briefcase'
        ],
        'emails.recruitment0' => [
            'name' => 'Rekrutmen',
            'sub' => 'Daftar pengajuan rekrutmen kandidat dan offering letter yang memerlukan tindakan Anda',
            'icon' => 'user-check'
        ],
        'emails.penyesuaian_upah0' => [
            'name' => 'Surat Pemberitahuan',
            'sub' => 'Daftar surat pemberitahuan dan penyesuaian upah yang memerlukan persetujuan Anda',
            'icon' => 'mail'
        ],
    ];

    $currentModule = $moduleMap[$filename] ?? [
        'name' => (!empty($trx[0]) ? $trx[0]->title : 'To Do List'),
        'sub' => 'Daftar pengajuan persetujuan yang memerlukan tindakan dan persetujuan Anda',
        'icon' => 'list'
    ];
@endphp

@section('title', $currentModule['name'] . ' - To Do List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    .page-title {
        margin-bottom: 12px;
    }
    .page-title .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .page-title .breadcrumb-item a,
    .page-title .breadcrumb-item span {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
    }
    .page-title .breadcrumb-item.active {
        color: #f05a00 !important;
        font-weight: 600;
    }
    .page-title .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #9ca3af;
        padding: 0 4px;
        font-size: 15px;
    }

    .todolist-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .todolist-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 16px;
    }
    .todolist-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #FFF9F1;
        border: 1px solid #fed7aa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f05a00;
        flex-shrink: 0;
    }
    .todolist-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px 0;
        line-height: 1.2;
    }
    .todolist-sub {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
    }
    .btn-back-custom {
        background: #f05a00 !important;
        border: 1.5px solid #f05a00 !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        padding: 9px 20px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        text-decoration: none !important;
        box-shadow: 0 2px 8px rgba(240, 90, 0, 0.25) !important;
        transition: all 0.2s ease !important;
        cursor: pointer;
    }
    .btn-back-custom svg {
        stroke: #ffffff !important;
    }
    .btn-back-custom:hover {
        background: #d95100 !important;
        border-color: #d95100 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(240, 90, 0, 0.35) !important;
        transform: translateY(-1px);
    }
    .btn-back-custom:active {
        transform: translateY(0);
    }

    .table-container-custom {
        margin: 20px 28px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow-x: auto;
        background: #fff;
    }

    /* Datatable styling */
    #_datatable {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
    }
    #_datatable thead th {
        background: #f8f9fa !important;
        color: #6b7280 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: .5px !important;
        text-transform: uppercase !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #e5e7eb !important;
        white-space: nowrap !important;
    }
    #_datatable tbody td {
        padding: 14px 16px !important;
        color: #334155 !important;
        font-size: 13px !important;
        border-bottom: 1px solid #f1f5f9 !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    #_datatable tbody tr:hover td {
        background-color: #fafbfc !important;
    }
    #_datatable tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Toolbar elements */
    .dataTables_wrapper {
        padding: 20px 28px 24px;
    }
    .dataTables_wrapper .dataTables_length {
        font-size: 13px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 6px 24px 6px 12px;
        margin: 0 6px;
        height: 36px;
        font-size: 13px;
        color: #374151;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter {
        position: relative;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 10px !important;
        background: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        height: 36px !important;
        padding: 6px 14px 6px 36px !important;
        font-size: 13px !important;
        width: 240px !important;
        outline: none !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #f05a00 !important;
        box-shadow: 0 0 0 3px rgba(240, 90, 0, 0.1) !important;
        background: #fff !important;
    }
    .filter-search-icon {
        position: absolute;
        left: 12px;
        top: 10px;
        color: #9ca3af;
        pointer-events: none;
        z-index: 2;
    }

    /* Export Buttons */
    .dt-buttons .dt-button {
        background: #fff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #374151 !important;
        margin: 0 3px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
        transition: all 0.2s ease !important;
    }
    .dt-buttons .dt-button:hover {
        background: #f9fafb !important;
        border-color: #cbd5e1 !important;
        color: #111827 !important;
    }

    /* Pagination */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 16px !important;
        padding-top: 0 !important;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        min-width: 32px !important;
        height: 32px !important;
        padding: 4px 12px !important;
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #6b7280 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        border: 1.5px solid #f05a00 !important;
        color: #f05a00 !important;
        background: #fff !important;
        font-weight: 700 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
        background: #f8fafc !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #cbd5e1 !important;
        border-color: #f1f5f9 !important;
        cursor: not-allowed !important;
        background: #fff !important;
    }
    .dataTables_wrapper .dataTables_info {
        font-size: 13px !important;
        color: #6b7280 !important;
        padding-top: 16px !important;
    }

    /* Action buttons in table */
    .action-btn-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-act {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .btn-act:hover {
        background: #FFF9F1;
        border-color: #fed7aa;
        color: #f05a00;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(240, 90, 0, 0.15);
    }
    .btn-act.pdf-btn {
        color: #dc2626;
        border-color: #fee2e2;
        background: #fef2f2;
    }
    .btn-act.pdf-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #b91c1c;
    }
    .btn-act.history-btn {
        color: #dc2626;
        border-color: #fee2e2;
        background: #fef2f2;
    }
    .btn-act.history-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #b91c1c;
    }

    .badge-status-custom {
        display: inline-block;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }
    .badge-id {
        display: inline-block;
        padding: 3px 8px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        font-family: monospace;
    }
</style>
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('todolist.index') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('todolist.index') }}">To Do List</a></li>
    <li class="breadcrumb-item active">{{ $currentModule['name'] }}</li>
@endsection

@section('content')
<div class="container-fluid" style="padding-top: 10px;">
    <!-- Main Card -->
    <div class="todolist-card">
        <!-- Header -->
        <div class="todolist-head">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div class="todolist-icon-box">
                    @if($currentModule['icon'] == 'clock')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14.5 14.5"/></svg>
                    @elseif($currentModule['icon'] == 'check-square')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    @elseif($currentModule['icon'] == 'calendar')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    @elseif($currentModule['icon'] == 'file-text')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    @elseif($currentModule['icon'] == 'log-out')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    @elseif($currentModule['icon'] == 'credit-card')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    @elseif($currentModule['icon'] == 'heart')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    @elseif($currentModule['icon'] == 'clipboard-check')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="4" width="14" height="18" rx="2.5" fill="#fff9f1" stroke="#f05a00"/>
                            <rect x="8.5" y="2" width="7" height="3.5" rx="1" fill="#fff9f1" stroke="#f05a00"/>
                            <line x1="8" y1="9" x2="14" y2="9" stroke="#f05a00"/>
                            <line x1="8" y1="12" x2="12" y2="12" stroke="#f05a00"/>
                            <circle cx="15.5" cy="16.5" r="4.5" fill="#fff9f1" stroke="#f05a00" stroke-width="2"/>
                            <polyline points="13.5 16.5 15 18 17.5 15" stroke="#f05a00" stroke-width="2"/>
                        </svg>
                    @elseif($currentModule['icon'] == 'users')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    @elseif($currentModule['icon'] == 'award')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    @elseif($currentModule['icon'] == 'user-plus')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    @elseif($currentModule['icon'] == 'book-open')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    @elseif($currentModule['icon'] == 'briefcase')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    @elseif($currentModule['icon'] == 'user-check')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
                    @elseif($currentModule['icon'] == 'mail')
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    @endif
                </div>
                <div>
                    <h1 class="todolist-title">To Do List &bull; {{ $currentModule['name'] }}</h1>
                    <p class="todolist-sub">{{ $currentModule['sub'] }}</p>
                </div>
            </div>

            <div>
                <a href="{{ route('todolist.index') }}" class="btn-back-custom">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>

        @if(Session::has('msg'))
        <div class="alert alert-success dark alert-dismissible fade show" role="alert" style="margin: 20px 28px 0; border-radius: 10px;">
            <h6 style="margin: 0;">{{ session('msg') }}</h6>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Table Container -->
        <div class="table-container-custom">
            <table class="display" id="_datatable">
                <thead>
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th style="width: 70px;">#ID</th>
                        <th>Formulir</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        @if(request('filename') == 'emails.penilaian0')
                            <th>Perusahaan</th>
                        @endif
                        <th>Unit Kerja</th>
                        @if(request('filename') == 'emails.reimburse0')
                            <th>Status</th>
                        @endif
                        <th>Created at</th>
                        <th class="text-center" style="width: 85px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx as $data)
                    <tr>
                        <td class="text-center">{{ ++$no }}</td>
                        <td><span class="badge-id">#{{ $data->id_trx }}</span></td>
                        <td><strong>{{ $data->title }}</strong></td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->jabatan }}</td>
                        @if(request('filename') == 'emails.penilaian0')
                            @php
                                $trxPen = App\Models\TrxPenilaian::find($data->id_trx);
                                $prop = $trxPen && $trxPen->karyawan_property ? json_decode($trxPen->karyawan_property, true) : [];
                            @endphp
                            <td>{{ $prop['perusahaan'] ?? '-' }}</td>
                        @endif
                        <td>{{ $data->unit_kerja }}</td>
                        @if(request('filename') == 'emails.reimburse0')
                            @php
                                $rStatus = App\Models\ReimburseStatus::find($data->id_status);
                            @endphp
                            <td>
                                <span class="badge-status-custom">
                                    {{ $rStatus ? $rStatus->status : '-' }}
                                </span>
                            </td>
                        @endif
                        <td>
                            <span class="text-muted">
                                {{ $data->created_at ? date('d M Y, H:i', strtotime($data->created_at)) : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="action-btn-group">
                                <a href="{{ $data->link }}" class="btn-act" data-toggle="tooltip" title="Detail / Review" target="_blank">
                                    <i class="icon-eye"></i>
                                </a>
                                @if($data->filename == 'emails.bpd0')
                                    <a href="{{ route('bpd.show', [Crypt::encrypt($data->id_trx), 'view'=>'history']) }}" class="btn-act history-btn" data-toggle="tooltip" title="History" target="_blank">
                                        <i class="icon-eye"></i>
                                    </a>
                                @elseif($data->filename == 'emails.penilaian0_outsourcing')
                                    <a href="{{ route('penilaian.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" class="btn-act pdf-btn" data-toggle="tooltip" title="Download PDF" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                @elseif($data->filename == 'emails.izin0')
                                    <a href="{{ route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" class="btn-act pdf-btn" data-toggle="tooltip" title="Download PDF" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                @elseif($data->filename == 'emails.pelatihan0')
                                    <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" class="btn-act pdf-btn" data-toggle="tooltip" title="Download PDF" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                @elseif($data->filename == 'emails.recruitment0')
                                    <a href="{{ route('recruitment.download_offering', Crypt::encrypt($data->id_trx)) }}" class="btn-act pdf-btn" data-toggle="tooltip" title="Download Offering" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding: 0 28px 24px;">
            <a href="{{ route('todolist.index') }}" class="btn-back-custom">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Back</span>
            </a>
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
$(document).ready(function() {
    $('#_datatable').DataTable({
        dom: "<'row mb-3 align-items-center'<'col-md-3'l><'col-md-6 text-center'B><'col-md-3 text-end'f>>" +
             "<'row'<'col-12'tr>>" +
             "<'row mt-3 align-items-center'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fa fa-clone me-1 text-muted"></i> Copy',
                className: 'dt-button'
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-text-o me-1 text-muted"></i> CSV',
                className: 'dt-button'
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o me-1" style="color: #107c41;"></i> Excel',
                className: 'dt-button'
            },
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf-o me-1" style="color: #dc2626;"></i> PDF',
                className: 'dt-button'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print me-1 text-muted"></i> Print',
                className: 'dt-button'
            }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search records...",
            lengthMenu: "Show _MENU_ entries",
            emptyTable: "Tidak ada data persetujuan pada modul ini.",
            info: "Showing <b>_START_ - _END_</b> of <b>_TOTAL_</b> entries",
            infoEmpty: "Showing 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                previous: "‹ Previous",
                next: "Next ›"
            }
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        initComplete: function() {
            var searchInput = $('.dataTables_filter input');
            searchInput.wrap('<div style="position:relative;display:inline-block;"></div>');
            searchInput.before('<i class="fa fa-search filter-search-icon"></i>');
        }
    });

    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection
