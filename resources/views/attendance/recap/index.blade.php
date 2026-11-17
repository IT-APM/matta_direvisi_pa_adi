@extends('layouts.simple.master')
@section('title', 'Attendance Recap')

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
    .recap-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .recap-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 16px;
    }
    .recap-icon-box {
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
    .recap-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px 0;
        line-height: 1.2;
    }
    .recap-sub {
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
        cursor: pointer;
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
        overflow-x: auto;
        background: #fff;
    }
    #_datatable {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
        min-width: 1200px;
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
    #_datatable tbody tr:last-child td {
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
    <li class="breadcrumb-item"><span>Attendance</span></li>
    <li class="breadcrumb-item active">Attendance Recap</li>
@endsection

@section('content')
<div class="container-fluid" style="padding-top: 10px;">
    <!-- Main Card -->
    <div class="recap-card">
        <!-- Header -->
        <div class="recap-head">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div class="recap-icon-box">
                    <svg viewBox="0 0 24 24" aria-hidden="true" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                        <path d="m9 16 2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="recap-title">Attendance Recap</h1>
                    <p class="recap-sub">Manage, review, and export employee attendance recap data</p>
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
                <form action="{{ route('recap_attendance.index') }}" method="get" class="needs-validation">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Tanggal Awal</label>
                            <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}" style="border-radius: 8px; font-size: 13px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Tanggal Akhir</label>
                            <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}" style="border-radius: 8px; font-size: 13px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Status Aktif</label>
                            <div style="border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; background: #fff;">
                                @foreach ($is_aktif as $data)
                                    <label class="me-3 mb-0" style="font-size: 13px;">
                                        <input class="checkbox_animated" name="is_aktif[]" type="checkbox" value="{{ $data['id'] }}" @if (in_array($data['id'], $arr_is_aktif)) checked @endif> {{ $data['is_aktif'] }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @can('hc')
                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Perusahaan</label>
                                <div style="max-height: 180px; overflow-y: auto; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; background: #fff;">
                                    @foreach ($perusahaan as $data)
                                        <label class="d-block mb-1" style="font-size: 12px;">
                                            <input class="checkbox_animated" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan()" value="{{ $data->id }}" @if (in_array($data->id, $arr_perusahaan)) checked @endif> {{ $data->nama }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Direktorat</label>
                                <div id="div_kode_divisi" style="max-height: 180px; overflow-y: auto; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; background: #fff;">
                                    @foreach ($divisi as $data)
                                        <label class="d-block mb-1" style="font-size: 12px;">
                                            <input class="checkbox_animated" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi()" value="{{ $data->id }}" @if (in_array($data->id, $arr_divisi)) checked @endif> {{ $data->nama }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Divisi</label>
                                <div id="div_kode_departemen" style="max-height: 180px; overflow-y: auto; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; background: #fff;">
                                    @foreach ($departemen as $data)
                                        <label class="d-block mb-1" style="font-size: 12px;">
                                            <input class="checkbox_animated" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen()" value="{{ $data->id }}" @if (in_array($data->id, $arr_departemen)) checked @endif> {{ $data->nama }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 11px;">Departemen</label>
                                <div id="div_kode_sub_departemen" style="max-height: 180px; overflow-y: auto; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; background: #fff;">
                                    @foreach ($sub_departemen as $data)
                                        <label class="d-block mb-1" style="font-size: 12px;">
                                            <input class="checkbox_animated" name="kode_sub_departemen[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_sub_departemen)) checked @endif> {{ $data->nama }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endcan

                    <div class="mt-3">
                        <button class="btn btn-warning me-2 text-white" id="filter" name="filter" value="true" type="submit" style="background:#f05a00;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:600;">
                            <i class="fa fa-filter me-1"></i> Terapkan
                        </button>
                        <a href="{{ route('recap_attendance.index') }}" class="btn btn-light border" style="border-radius:8px;padding:8px 18px;font-size:13px;font-weight:500;">
                            <i class="fa fa-refresh me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container-custom">
            <table class="table" id="_datatable">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">NO</th>
                        <th>TANGGAL AWAL</th>
                        <th>TANGGAL AKHIR</th>
                        <th>WORKING GROUP</th>
                        <th>NIP</th>
                        <th>NAMA KARYAWAN</th>
                        <th>LEVEL</th>
                        <th>DEPARTEMEN</th>
                        <th>EMAIL KARYAWAN</th>
                        <th>NAMA LAYER 1</th>
                        <th>EMAIL LAYER 1</th>
                        <th style="text-align: center;">JUMLAH HARI KERJA</th>
                        <th style="text-align: center;">JUMLAH KEHADIRAN</th>
                        <th style="text-align: center;">PERSENTASE KEHADIRAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 0; @endphp
                    @foreach ($trx as $data)
                        @php
                            $karyawan = App\Models\Karyawan::where(['nik' => $data['nik']])->first();
                            $layer1 = !empty($karyawan->layer1) ? App\Models\Karyawan::where(['nik' => $karyawan->layer1])->first() : null;
                            $pct = round($data['persentase']);
                        @endphp
                        <tr>
                            <td style="text-align: center; color: #9ca3af;">{{ ++$no }}</td>
                            <td>{{ \Carbon\Carbon::parse($date_from)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($date_to)->format('d-m-Y') }}</td>
                            <td>{{ $karyawan->_wg->wg_name ?? '-' }}</td>
                            <td style="font-family: monospace; color: #64748b;">{{ $data['nik'] }}</td>
                            <td style="font-weight: 600; color: #1e293b;">{{ $karyawan->nama ?? '-' }}</td>
                            <td style="color: #475569;">{{ $karyawan->_level->level ?? '-' }}</td>
                            <td style="color: #475569;">{{ $karyawan->_subdepartemen->nama ?? ($karyawan->_departemen->nama ?? '-') }}</td>
                            <td style="color: #475569;">{{ $karyawan->email ?? '-' }}</td>
                            <td style="color: #475569;">{{ $layer1 ? $layer1->nama : '-' }}</td>
                            <td style="color: #475569;">{{ $layer1 ? $layer1->email : '-' }}</td>
                            <td style="text-align: center; font-family: monospace; font-weight: 600; color: #1e293b;">{{ $data['arr_base'] }}</td>
                            <td style="text-align: center; font-family: monospace; font-weight: 600; color: #1e293b;">{{ $data['arr_clock'] }}</td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: {{ $pct >= 90 ? '#dcfce7; color: #15803d' : ($pct >= 75 ? '#e0f2fe; color: #0369a1' : '#ffedd5; color: #c2410c') }}; padding: 4px 12px; font-size: 12px; border-radius: 20px; font-weight: 700;">
                                    {{ $pct }} %
                                </span>
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
    var table = $('#_datatable').DataTable({
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
            searchPlaceholder: "Search attendance recap...",
            lengthMenu: "Show _MENU_ entries",
            emptyTable: "Tidak ada data rekap presensi pada periode ini.",
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
});
</script>
@endsection
