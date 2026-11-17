@extends('layouts.simple.master')
@section('title', 'Birthday Email - MATTA Workplace')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
  /* Birthday Email User-Style Page & Table */
  .birthday-container {
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
  .bday-tabs-pill {
    display: inline-flex;
    align-items: center;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 9999px;
    gap: 4px;
  }
  .bday-tab-btn {
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
  .bday-tab-btn:hover {
    color: #ff5400;
  }
  .bday-tab-btn.active, .bday-tab-btn[aria-selected="true"] {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
  }
  .bday-tab-btn .tab-count {
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 9999px;
    background: #e2e8f0;
    color: #475569;
    transition: all .2s;
  }
  .bday-tab-btn.active .tab-count {
    background: #ff5400;
    color: #ffffff;
  }

  /* Action Bar for Current Queue */
  .bday-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 28px;
    background: #fafbfc;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
  }
  .btn-bday-exec {
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
  .btn-bday-exec:hover {
    background: #e04a00;
    box-shadow: 0 4px 12px rgba(255, 84, 0, 0.35);
    color: #ffffff;
  }
  .btn-bday-del {
    background: #ffffff;
    border: 1px solid #fca5a5;
    color: #dc2626;
    border-radius: 9999px;
    padding: 8px 18px;
    font-weight: 600;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all .18s ease;
  }
  .btn-bday-del:hover {
    background: #fef2f2;
    border-color: #ef4444;
    color: #b91c1c;
  }

  /* Table Container */
  .table-wrap {
    padding: 16px 28px 24px;
    background: #ffffff;
  }
  #_datatable_current, #_datatable_history {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    font-size: 13px !important;
  }
  #_datatable_current thead th, #_datatable_history thead th {
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
  }
  #_datatable_current tbody td, #_datatable_history tbody td {
    padding: 12px 14px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
    border-bottom: none !important;
    color: #334155 !important;
  }
  #_datatable_current tbody tr:hover, #_datatable_history tbody tr:hover {
    background-color: #fffaf5 !important;
  }
  .emp-name-badge {
    font-weight: 600;
    color: #0f172a;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .emp-avatar-dot {
    width: 28px;
    height: 28px;
    border-radius: 9999px;
    background: #fff7ed;
    color: #ea580c;
    border: 1px solid #ffedd5;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
  }
  .nip-pill {
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 600;
    font-family: monospace;
    font-size: 12px;
    color: #475569;
    border: 1px solid #e2e8f0;
    display: inline-block;
  }
  .bday-date-text {
    font-weight: 600;
    color: #334155;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .subject-pill {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #ffedd5;
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .time-stamp-text {
    font-size: 12px;
    color: #64748b;
  }
</style>
@endsection

@section('breadcrumb-title')
<h3>Birthday Email</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Birthday</li>
<li class="breadcrumb-item active">Email</li>
@endsection

@section('content')
<div class="container-fluid birthday-container">

    @if(Session::has('msg'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; padding: 14px 20px; margin-bottom: 20px;">
        <div class="d-flex align-items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span style="font-weight: 600; font-size: 13.5px;">{{ session('msg') }}</span>
        </div>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- MAIN DATA CARD (USER-STYLE) -->
    <div class="dt-card">

        <!-- HEADER -->
        <div class="dt-head">
            <div class="dt-title-wrap">
                <div class="dt-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 12 20 22 4 22 4 12"/>
                        <rect width="20" height="5" x="2" y="7"/>
                        <line x1="12" y1="22" x2="12" y2="7"/>
                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="dt-title">Birthday Email</h1>
                    <p class="dt-desc">Kelola dan kirimkan ucapan ulang tahun otomatis ke email seluruh karyawan ALITA</p>
                </div>
            </div>

            <!-- TAB PILL SWITCHER -->
            <div class="bday-tabs-pill" role="tablist">
                <button type="button" class="bday-tab-btn active" id="pills-current-tab" data-bs-toggle="pill" data-bs-target="#pills-current" role="tab" aria-controls="pills-current" aria-selected="true">
                    <span class="dot-live" style="width: 7px; height: 7px; border-radius: 9999px; background: #ff5400; display: inline-block;"></span>
                    <span>Current Queue</span>
                    <span class="tab-count">{{ count($trx_current) }}</span>
                </button>
                <button type="button" class="bday-tab-btn" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" role="tab" aria-controls="pills-history" aria-selected="false">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>History Log</span>
                    <span class="tab-count">{{ count($trx_history) }}</span>
                </button>
            </div>
        </div>

        <!-- TAB CONTENT -->
        <div class="tab-content" id="pills-warningtabContent">

            <!-- TAB 1: CURRENT QUEUE -->
            <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                <form action="{{ route('birthday.send_email_exe') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda yakin ingin memproses data email terpilih?');">
                    @csrf
                    <input type="hidden" id="tipe" name="tipe" value="current">

                    <!-- ACTION BAR -->
                    <div class="bday-action-bar">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 13px; font-weight: 600; color: #475569;">
                                Antrean Pengiriman Hari Ini
                            </span>
                            <span class="badge" style="background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; font-size: 11px; font-weight: 700;">
                                {{ count($trx_current) }} Karyawan
                            </span>
                        </div>

                        @if(count($trx_current) > 0)
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn-bday-exec" type="submit" name="submit" id="submit" value="execute" title="Kirimkan email ke karyawan terpilih">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"/>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                </svg>
                                <span>Execute (Kirim Email)</span>
                            </button>
                            <button class="btn-bday-del" type="submit" name="submit" value="delete" title="Hapus antrean terpilih">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                <span>Delete</span>
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- TABLE WRAP -->
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="display table" id="_datatable_current" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 40px; text-align: center;">
                                            <input type="checkbox" onClick="toggle_status_current(this)" style="cursor: pointer; width: 16px; height: 16px; accent-color: #ff5400;" title="Pilih Semua" />
                                        </th>
                                        <th style="width: 50px; text-align: center;">No</th>
                                        <th>Nama Karyawan</th>
                                        <th>NIP</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Subject Email</th>
                                        <th>Waktu Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 0; @endphp
                                    @foreach ($trx_current as $data)
                                        @php
                                            $nik_property = is_array($data->nik_property) ? $data->nik_property : (json_decode($data->nik_property, true) ?? []);
                                            $email = is_array($data->email) ? $data->email : (json_decode($data->email, true) ?? []);
                                            $namaKaryawan = $nik_property['nama'] ?? ($data->_nik?->nama ?? $data->nik);
                                            $nikKaryawan  = $nik_property['nik'] ?? $data->nik;
                                            $subjectEmail = is_array($email) ? ($email['subject'] ?? ($data->email ?: 'Happy Birthday')) : ($data->email ?: 'Happy Birthday');
                                            $initials = collect(explode(' ', $namaKaryawan))->map(fn($w) => substr($w, 0, 1))->take(2)->join('');
                                        @endphp
                                        <tr>
                                            <td style="text-align: center;">
                                                <input id="id_current[]" name="id_current[]" type="checkbox" value="{{ $data->id }}" style="cursor: pointer; width: 16px; height: 16px; accent-color: #ff5400;">
                                            </td>
                                            <td style="text-align: center; font-weight: 600; color: #94a3b8;">{{ ++$no }}</td>
                                            <td>
                                                <div class="emp-name-badge">
                                                    <span class="emp-avatar-dot">{{ $initials ?: 'AL' }}</span>
                                                    <span>{{ $namaKaryawan }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="nip-pill">{{ $nikKaryawan }}</span>
                                            </td>
                                            <td>
                                                <span class="bday-date-text">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                                                        <path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                                                    </svg>
                                                    {{ !empty($data->birthday) ? \Carbon\Carbon::parse($data->birthday)->format('d-m-Y') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="subject-pill">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z"/>
                                                        <path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"/>
                                                    </svg>
                                                    {{ $subjectEmail }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="time-stamp-text">{{ $data->created_at }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TAB 2: HISTORY LOG -->
            <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                
                <div class="bday-action-bar">
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size: 13px; font-weight: 600; color: #475569;">
                            Riwayat Email yang Telah Terkirim
                        </span>
                        <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 11px; font-weight: 700;">
                            {{ count($trx_history) }} Riwayat
                        </span>
                    </div>
                </div>

                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="display table" id="_datatable_history" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Nama Karyawan</th>
                                    <th>NIP</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Subject Email</th>
                                    <th>Waktu Terkirim</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp
                                @foreach ($trx_history as $data)
                                    @php
                                        $nik_property = is_array($data->nik_property) ? $data->nik_property : (json_decode($data->nik_property, true) ?? []);
                                        $email = is_array($data->email) ? $data->email : (json_decode($data->email, true) ?? []);
                                        $namaKaryawan = $nik_property['nama'] ?? ($data->_nik?->nama ?? $data->nik);
                                        $nikKaryawan  = $nik_property['nik'] ?? $data->nik;
                                        $subjectEmail = is_array($email) ? ($email['subject'] ?? ($data->email ?: 'Happy Birthday')) : ($data->email ?: 'Happy Birthday');
                                        $initials = collect(explode(' ', $namaKaryawan))->map(fn($w) => substr($w, 0, 1))->take(2)->join('');
                                    @endphp
                                    <tr>
                                        <td style="text-align: center; font-weight: 600; color: #94a3b8;">{{ ++$no }}</td>
                                        <td>
                                            <div class="emp-name-badge">
                                                <span class="emp-avatar-dot">{{ $initials ?: 'AL' }}</span>
                                                <span>{{ $namaKaryawan }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="nip-pill">{{ $nikKaryawan }}</span>
                                        </td>
                                        <td>
                                            <span class="bday-date-text">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect width="18" height="18" x="3" y="4" rx="2"/>
                                                    <path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                                                </svg>
                                                {{ !empty($data->birthday) ? \Carbon\Carbon::parse($data->birthday)->format('d-m-Y') : '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="subject-pill">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z"/>
                                                    <path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"/>
                                                </svg>
                                                {{ $subjectEmail }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="time-stamp-text">{{ $data->created_at }}</span>
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
function toggle_status_current(source) {
    var checkboxes = document.getElementsByName('id_current[]');
    for(var i=0, n=checkboxes.length; i<n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

const bdayDtOptions = {
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
        searchPlaceholder: "Cari data...",
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

const tblCurrent = $('#_datatable_current').DataTable(bdayDtOptions);
const tblHistory = $('#_datatable_history').DataTable(bdayDtOptions);

$('button[data-bs-toggle="pill"], a[data-bs-toggle="pill"]').on('shown.bs.tab', function () {
    $($.fn.dataTable.tables({ visible: true, api: true })).DataTable().columns.adjust();
});
</script>
@endsection
