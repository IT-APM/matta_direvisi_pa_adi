@extends('layouts.simple.master')

@section('title', 'Payroll Slip')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
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
    .payroll-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .payroll-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 16px;
    }
    .payroll-icon-box {
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
    .payroll-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px 0;
        line-height: 1.2;
    }
    .payroll-sub {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
    }
    .btn-filter-periode {
        background: #f05a00 !important;
        border-color: #f05a00 !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        padding: 10px 22px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        box-shadow: 0 2px 6px rgba(240, 90, 0, 0.2) !important;
    }
    .btn-filter-periode:hover {
        background: #d95100 !important;
        color: #fff !important;
    }
    .filter-panel {
        padding: 18px 28px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-container-custom {
        margin: 0 28px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }
    #_datatable1 {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
    }
    #_datatable1 thead th {
        background: #f8f9fa !important;
        color: #6b7280 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: .5px !important;
        text-transform: uppercase !important;
        padding: 14px 18px !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    #_datatable1 tbody td {
        padding: 14px 18px !important;
        color: #334155 !important;
        font-size: 13px !important;
        border-bottom: 1px solid #f1f5f9 !important;
        vertical-align: middle !important;
    }
    #_datatable1 tbody tr:last-child td {
        border-bottom: none !important;
    }
    /* Toolbar elements */
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
        width: 260px !important;
        outline: none !important;
    }
    .filter-search-icon {
        position: absolute;
        left: 12px;
        top: 10px;
        color: #9ca3af;
        pointer-events: none;
        z-index: 2;
    }
    /* Buttons */
    .dt-buttons .dt-button {
        background: #fff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #374151 !important;
        margin-left: 6px !important;
        box-shadow: none !important;
    }
    .dt-buttons .dt-button:hover {
        background: #f9fafb !important;
        border-color: #cbd5e1 !important;
    }
    /* Pagination */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 0 !important;
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
        padding: 0 !important;
    }
</style>
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('matta.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><span>Payroll</span></li>
<li class="breadcrumb-item active">Payroll Slip</li>
@endsection

@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$hco = explode(',', App\Models\MtrUtility::find(32)->informasi ?? '');
@endphp

@section('content')
<div class="container-fluid" style="padding-top: 10px;">
    <!-- Main Card -->
    <div class="payroll-card">
        <!-- Header -->
        <div class="payroll-head">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div class="payroll-icon-box">
                    <svg viewBox="0 0 24 24" aria-hidden="true" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="15" x="2" y="4.5" rx="2.5"/>
                        <circle cx="7.5" cy="12" r="2.2"/>
                        <line x1="12" y1="10" x2="18" y2="10"/>
                        <line x1="12" y1="14" x2="16" y2="14"/>
                    </svg>
                </div>
                <div>
                    <h1 class="payroll-title">Payroll Slip</h1>
                    <p class="payroll-sub">Manage, review, and download official employee payslips</p>
                </div>
            </div>

            <div>
                <button class="btn btn-filter-periode" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter">
                    <i class="fa fa-search"></i>
                    <span>Filter Periode</span>
                </button>
            </div>
        </div>

        <!-- Filter Collapse Panel -->
        <div class="collapse" id="collapse_filter">
            <div class="filter-panel">
                <form action="{{ route('payroll.slip', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik)) }}" method="get" class="needs-validation" novalidate="">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Bulan-Tahun Dari</label>
                            <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control" type="month" value="{{ $bulan_tahun_dari }}" style="border-radius: 8px; font-size: 13px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Bulan-Tahun Sampai</label>
                            <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control" type="month" value="{{ $bulan_tahun_sampai }}" style="border-radius: 8px; font-size: 13px;">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-warning me-2 text-white" id="filter" name="filter" value="true" type="submit" style="background:#f05a00;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;">
                                <i class="fa fa-filter me-1"></i> Terapkan
                            </button>
                            <a href="{{ route('payroll.slip', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik)) }}" class="btn btn-light border" style="border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container-custom">
            <table class="table" id="_datatable1">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">NO</th>
                        <th>YEAR</th>
                        <th>MONTH</th>
                        <th>NAME</th>
                        <th>NIP</th>
                        <th style="text-align: center;">PAYSLIP</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 0; @endphp
                    @foreach ($payroll as $data)
                    <tr>
                        <td style="text-align: center; color: #9ca3af;">{{ ++$no }}</td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $data->tahun }}</td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $bulan[$data->bulan - 1] ?? $data->bulan }}</td>
                        <td style="color: #334155;">{{ $data->_nik->nama ?? auth()->user()->name }}</td>
                        <td style="font-family: monospace; color: #64748b;">{{ $data->nik }}</td>
                        <td style="text-align: center;">
                            @if(in_array(auth()->user()->nik, $hco))
                                <a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf']) }}" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" title="Unduh / Lihat Slip Gaji" target="_blank" style="border-radius: 6px; padding: 4px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                            @else
                                @if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday()))
                                    <a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf']) }}" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" title="Unduh / Lihat Slip Gaji" target="_blank" style="border-radius: 6px; padding: 4px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa fa-file-pdf-o"></i> PDF
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 12px;">-</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @foreach ($payroll_h as $data)
                    <tr>
                        <td style="text-align: center; color: #9ca3af;">{{ ++$no }}</td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $data->tahun }}</td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $bulan[$data->bulan - 1] ?? $data->bulan }}</td>
                        <td style="color: #334155;">{{ $data->_nik->nama ?? auth()->user()->name }}</td>
                        <td style="font-family: monospace; color: #64748b;">{{ $data->nik }}</td>
                        <td style="text-align: center;">
                            @if(in_array(auth()->user()->nik, $hco))
                                <a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h']) }}" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" title="Unduh / Lihat Slip Gaji" target="_blank" style="border-radius: 6px; padding: 4px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                            @else
                                @if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday()))
                                    <a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h']) }}" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" title="Unduh / Lihat Slip Gaji" target="_blank" style="border-radius: 6px; padding: 4px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa fa-file-pdf-o"></i> PDF
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 12px;">-</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#_datatable1').DataTable({
        dom: "<'row p-4 pb-2 align-items-center'<'col-md-2'l><'col-md-5'f><'col-md-5 text-end'B>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row p-4 pt-3 align-items-center'<'col-sm-6'i><'col-sm-6 text-end'p>>",
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
            searchPlaceholder: "Search payslips...",
            lengthMenu: "Show _MENU_ entries",
            emptyTable: "Tidak ada data slip gaji pada periode ini.",
            info: "Showing <b>_START_ - _END_</b> of <b>_TOTAL_</b> payslips",
            infoEmpty: "Showing 0 payslips",
            infoFiltered: "(filtered from _MAX_ total payslips)",
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
});
</script>
@endsection
