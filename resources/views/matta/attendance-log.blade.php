@extends('matta.layouts.app', ['activeNav' => 'attendance-log'])

@section('title', 'Attendance Log')

@section('content')
<main class="main" id="main" data-page="attendance-log" data-server-rendered="true" style="padding: 24px 32px 48px;">
  <!-- Breadcrumb -->
  <div class="page-top" style="margin-bottom: 20px;">
    <nav class="breadcrumb" aria-label="Breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280;">
      <a href="{{ route('matta.dashboard') }}" style="color: #6b7280; text-decoration: none;">Home</a>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><path d="m9 18 6-6-6-6"/></svg>
      <span style="color: #6b7280;">Attendance</span>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><path d="m9 18 6-6-6-6"/></svg>
      <span class="current" aria-current="page" style="color: #f05a00; font-weight: 600;">Attendance Log</span>
    </nav>
  </div>

  <!-- Main Data Card -->
  <section class="dt-card" id="panel-raw" data-datatable data-server-rendered="true" data-file="attendance-log" data-label="log presensi" data-empty-message="Tidak ada data log presensi pada periode ini." data-info-english="true" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); overflow: hidden;">
    <!-- Header -->
    <div class="dt-head" style="display: flex; align-items: center; justify-content: space-between; padding: 24px 28px; border-bottom: 1px solid #f1f5f9; gap: 16px; flex-wrap: wrap;">
      <div class="dt-title-wrap" style="display: flex; align-items: center; gap: 16px;">
        <div class="dt-icon" style="width: 48px; height: 48px; border-radius: 12px; background: #FFF9F1; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #f05a00; flex-shrink: 0;">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div>
          <h1 class="dt-title" style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 4px 0; line-height: 1.2;">Attendance Log</h1>
          <p class="dt-desc" style="font-size: 13px; color: #9ca3af; margin: 0;">Menampilkan seluruh rekaman Clock-In dan Clock-Out beserta titik koordinat GPS</p>
        </div>
      </div>

      <div class="dt-actions" style="display: flex; align-items: center; gap: 10px;">
        @if($isFiltered)
          <a href="{{ route('matta.attendance.log') }}" class="btn btn-outline" style="height: 38px; border-radius: 10px; font-size: 13px; padding: 0 14px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"/>
              <path d="m6 6 12 12"/>
            </svg>
            <span>Reset Filter</span>
          </a>
        @endif

        <button class="btn btn-primary" type="button" data-modal-open="modal-filter-attendance" onclick="openFilterModal(event)" style="background: #f05a00; border: none; color: #ffffff; border-radius: 10px; padding: 10px 22px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(240, 90, 0, 0.2);">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
          </svg>
          <span>Filter</span>
          @if($isFiltered)
            <span style="background: #fff; color: #f05a00; border-radius: 10px; padding: 1px 7px; font-size: 11px; font-weight: 700;">Aktif</span>
          @endif
        </button>
      </div>
    </div>

    <!-- Toolbar -->
    <div class="dt-toolbar" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 28px 16px; flex-wrap: wrap; gap: 16px;">
      <!-- Show entries -->
      <label class="dt-length" style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280; margin: 0; flex-shrink: 0;">
        <span>Show</span>
        <select class="form-control" data-dt-length aria-label="Jumlah baris" style="width: 70px; height: 36px; padding: 0 20px 0 10px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 13px; background: #fff;">
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span>entries</span>
      </label>

      <!-- Search input -->
      <label class="dt-search" style="margin: 0 auto 0 20px; flex: 0 1 320px; min-width: 220px;">
        <span class="input-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; pointer-events: none;">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
          </svg>
          <input class="form-control" type="search" placeholder="Search attendance log..." data-dt-search style="padding: 8px 14px 8px 36px; border-radius: 10px; background: #f9fafb; border: 1px solid #e5e7eb; height: 36px; width: 100%; font-size: 13px; outline: none; box-sizing: border-box;">
        </span>
      </label>

      <!-- Export Buttons -->
      <div class="dt-exports" style="display: flex; gap: 6px; flex-wrap: wrap; margin-left: auto;">
        <button class="btn btn-outline" type="button" data-dt-export="copy" style="height: 36px; padding: 0 12px; gap: 6px; font-size: 13px; font-weight: 500; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; display: inline-flex; align-items: center; cursor: pointer;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="15" height="15" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
          <span>Copy</span>
        </button>
        <button class="btn btn-outline" type="button" data-dt-export="csv" style="height: 36px; padding: 0 12px; gap: 6px; font-size: 13px; font-weight: 500; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; display: inline-flex; align-items: center; cursor: pointer;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="15" height="15" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
          <span>CSV</span>
        </button>
        <button class="btn btn-outline xls" type="button" data-dt-export="excel" style="height: 36px; padding: 0 12px; gap: 6px; font-size: 13px; font-weight: 500; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; display: inline-flex; align-items: center; cursor: pointer;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="15" height="15" fill="none" stroke="#107c41" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #107c41;"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
          <span>Excel</span>
        </button>
        <button class="btn btn-outline pdf" type="button" data-dt-export="pdf" style="height: 36px; padding: 0 12px; gap: 6px; font-size: 13px; font-weight: 500; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; display: inline-flex; align-items: center; cursor: pointer;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="15" height="15" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #dc2626;"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
          <span>PDF</span>
        </button>
        <button class="btn btn-outline" type="button" data-dt-export="print" style="height: 36px; padding: 0 12px; gap: 6px; font-size: 13px; font-weight: 500; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; display: inline-flex; align-items: center; cursor: pointer;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="15" height="15" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
          <span>Print</span>
        </button>
      </div>
    </div>

    <!-- Table Wrap -->
    <div class="table-wrap" style="margin: 0 28px 24px; border: 1px solid #e5e7eb; border-radius: 12px; overflow-x: auto; background: #fff;">
      <table class="dt" style="width: 100%; border-collapse: collapse; font-size: 13px; min-width: 1400px;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 1px solid #e5e7eb;">
            <th class="num" data-sort="num" style="width: 55px; text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              NO
            </th>
            <th data-sort="date" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              DATE
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              TIMESTAMP
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              NAME
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              NIP
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              ID_FP
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              PERUSAHAAN
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              DIREKTORAT
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              DIVISI
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              DEPARTEMEN
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              LOCATION
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              WORKING GROUP
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              ACTION
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              LONGITUDE
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              LATITUDE
            </th>
            <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              ADDRESS
            </th>
            <th class="center" data-noexport style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              MAPS
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              STATUS
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              DELAY
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              TYPE
            </th>
            <th class="center" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              SOURCE
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse($rawLogs as $idx => $r)
            @php
              $detailObj = [
                'eyebrow' => 'Attendance',
                'title' => $r['action'] . ' · ' . $r['timestamp'],
                'icon' => 'clock',
                'badges' => [
                  [$r['action'], $r['isClockIn'] ? 'blue' : 'green'],
                  [$r['status'], strtolower($r['status']) === 'late-in' ? 'orange' : 'green'],
                  [$r['source'], 'orange'],
                ],
                'person' => ['name' => $r['name'], 'nip' => $r['nip'], 'role' => $r['company']],
                'sections' => [
                  [
                    'h' => 'Data Presensi',
                    'rows' => [
                      ['Tanggal', $r['dateStr']],
                      ['Timestamp', $r['timestamp']],
                      ['Action', $r['action']],
                      ['Status', $r['status']],
                      ['Delay', $r['delay']],
                      ['Type', $r['type']],
                      ['Working Group', $r['wg']],
                      ['Source', $r['source']],
                      ['ID_FP', (string) $r['id_fp']],
                    ]
                  ],
                  [
                    'h' => 'Organisasi',
                    'rows' => [
                      ['Perusahaan', $r['company']],
                      ['Direktorat', $r['directorate']],
                      ['Divisi', $r['division']],
                      ['Departemen', $r['department']],
                      ['Lokasi', $r['location']],
                    ]
                  ],
                  [
                    'h' => 'Lokasi Presensi',
                    'rows' => [
                      ['Longitude', $r['lng']],
                      ['Latitude', $r['lat']],
                      ['Address', $r['address']],
                    ]
                  ]
                ],
                'map' => [
                  'lat' => $r['lat'],
                  'lng' => $r['lng'],
                  'label' => $r['address'],
                  'valid' => ($r['lat'] !== '0' && $r['lng'] !== '0'),
                ]
              ];

              $isClockIn = $r['isClockIn'];
              $isLate = str_contains(strtolower($r['status']), 'late');
            @endphp
            <tr data-dept="{{ $r['directorate'] }}" data-detail='@json($detailObj)' style="border-bottom: 1px solid #f1f5f9; transition: background .15s ease;">
              <td class="num" data-dt-no style="text-align: center; padding: 14px 16px; color: #9ca3af; white-space: nowrap;">{{ $idx + 1 }}</td>
              <td class="strong" style="padding: 14px 16px; font-weight: 600; color: #1e293b; white-space: nowrap;">{{ $r['dateStr'] }}</td>
              <td class="mono" style="padding: 14px 16px; font-family: monospace; color: #475569; white-space: nowrap;">{{ $r['timestamp'] }}</td>
              <td class="cell-2" style="padding: 14px 16px; white-space: nowrap;">
                <div class="main-line" style="font-weight: 600; color: #1e293b;">{{ $r['name'] }}</div>
                <div class="sub-line" style="font-size: 11px; color: #94a3b8;">{{ $r['company'] }}</div>
              </td>
              <td class="mono" style="padding: 14px 16px; font-family: monospace; color: #64748b; white-space: nowrap;">{{ $r['nip'] }}</td>
              <td class="center mono" style="text-align: center; padding: 14px 16px; font-family: monospace; color: #94a3b8; white-space: nowrap;">{{ $r['id_fp'] }}</td>
              <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $r['company'] }}</td>
              <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $r['directorate'] }}</td>
              <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $r['division'] }}</td>
              <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $r['department'] }}</td>
              <td class="strong" style="padding: 14px 16px; font-weight: 600; color: #334155; white-space: nowrap;">{{ $r['location'] }}</td>
              <td class="muted-2" style="padding: 14px 16px; color: #64748b; white-space: nowrap;">{{ $r['wg'] }}</td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                @if($isClockIn)
                  <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #2563eb;"></span>
                    {{ $r['action'] }}
                  </span>
                @else
                  <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span>
                    {{ $r['action'] }}
                  </span>
                @endif
              </td>
              <td class="center mono" style="text-align: center; padding: 14px 16px; font-family: monospace; color: #64748b; font-size: 12px; white-space: nowrap;">{{ $r['lng'] }}</td>
              <td class="center mono" style="text-align: center; padding: 14px 16px; font-family: monospace; color: #64748b; font-size: 12px; white-space: nowrap;">{{ $r['lat'] }}</td>
              <td style="padding: 14px 16px; color: #475569; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $r['address'] }}">{{ $r['address'] }}</td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                <button type="button" class="map-pin" data-row-detail data-focus="map" aria-label="Lihat lokasi" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #fed7aa; background: #FFF9F1; color: #f05a00; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s;">
                  <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                    <circle cx="12" cy="10" r="3"/>
                  </svg>
                </button>
              </td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                @if($isLate)
                  <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">{{ $r['status'] }}</span>
                @else
                  <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">{{ $r['status'] }}</span>
                @endif
              </td>
              <td class="center muted-2" style="text-align: center; padding: 14px 16px; color: #94a3b8; font-family: monospace; white-space: nowrap;">{{ $r['delay'] }}</td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                <span style="padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">{{ $r['type'] }}</span>
              </td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                <span style="padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #FFF9F1; color: #f05a00; border: 1px solid #ffedd5;">{{ $r['source'] }}</span>
              </td>
            </tr>
          @empty
            <tr class="dt-empty">
              <td colspan="21" style="text-align: center; padding: 48px 16px; color: #94a3b8; font-size: 14px; background: #ffffff;">
                Tidak ada data log presensi pada periode ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="dt-footer" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 28px 24px; flex-wrap: wrap; gap: 12px;">
      <div class="dt-info" data-dt-info style="font-size: 13px; color: #6b7280;">
        @if(count($rawLogs) > 0)
          Showing <b>1 - {{ min(10, count($rawLogs)) }}</b> of <b>{{ count($rawLogs) }}</b> logs
        @else
          Showing <b>0</b> of <b>0</b> logs
        @endif
      </div>
      <div class="pager" data-dt-pager style="display: flex; align-items: center; gap: 6px;">
        <button type="button" data-p="prev" disabled style="min-width: 32px; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #9ca3af; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: not-allowed;">‹ Previous</button>
        <button type="button" class="active" aria-current="page" style="min-width: 32px; height: 32px; padding: 0 10px; border-radius: 8px; border: 1.5px solid #f05a00; color: #f05a00; background: #fff; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; justify-content: center;">1</button>
        <button type="button" data-p="next" {{ count($rawLogs) <= 10 ? 'disabled' : '' }} style="min-width: 32px; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: {{ count($rawLogs) <= 10 ? '#9ca3af' : '#374151' }}; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: {{ count($rawLogs) <= 10 ? 'not-allowed' : 'pointer' }};">Next ›</button>
      </div>
    </div>
  </section>
</main>
@endsection

@section('modals')
  <!-- ==================== POP UP MODAL FILTER ==================== -->
  <div class="modal-backdrop" id="modal-filter-attendance" aria-hidden="true" style="position: fixed; inset: 0; z-index: 99999; background: rgba(15, 23, 42, 0.55); backdrop-filter: blur(3px); display: none; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-filter-title" style="max-width: 1040px; width: 100%; max-height: 90vh; background: #ffffff; border-radius: 18px; display: flex; flex-direction: column; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid #e2e8f0; overflow: hidden; margin: auto; animation: popIn .2s ease-out;">
      
      <!-- Modal Header -->
      <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 28px; border-bottom: 1px solid #f1f5f9; background: #ffffff;">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div style="width: 40px; height: 40px; border-radius: 10px; background: #FFF9F1; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #f05a00; flex-shrink: 0;">
            <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="20" height="20" fill="none" stroke="#f05a00" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/>
              <path d="m21 21-4.3-4.3"/>
            </svg>
          </div>
          <div>
            <h3 class="modal-title" id="modal-filter-title" style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 2px 0;">Filter Attendance Log</h3>
            <p style="font-size: 13px; color: #64748b; margin: 0;">Tentukan parameter tanggal, karyawan, status, dan grup kerja untuk memfilter data presensi</p>
          </div>
        </div>
        <button type="button" onclick="closeFilterModal()" aria-label="Tutup" style="background: #f1f5f9; border: none; cursor: pointer; color: #64748b; width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all .15s ease;">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"/>
            <path d="m6 6 12 12"/>
          </svg>
        </button>
      </div>

      <!-- Modal Body (Form with 4 columns) -->
      <form id="form-filter-attendance" action="{{ route('matta.attendance.log') }}" method="GET" style="margin: 0; display: flex; flex-direction: column; flex: 1; overflow: hidden;">
        <div style="padding: 24px 28px; overflow-y: auto; flex: 1; background: #fafafa;">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; align-items: start;">
            
            <!-- Column 1: Date & Karyawan -->
            <div style="background: #ffffff; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
              <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <span>Date</span>
              </div>
              
              <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b; margin-bottom: 4px; letter-spacing: 0.3px;">From</label>
              <input type="date" name="date_from" value="{{ $date_from }}" class="form-control" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%; padding: 0 10px; font-size: 13px; margin-bottom: 12px; box-sizing: border-box; background: #fff;">

              <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b; margin-bottom: 4px; letter-spacing: 0.3px;">To</label>
              <input type="date" name="date_to" value="{{ $date_to }}" class="form-control" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%; padding: 0 10px; font-size: 13px; margin-bottom: 16px; box-sizing: border-box; background: #fff;">

              <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Karyawan</span>
              </div>
              <select name="str_att_nik_nama" class="form-control" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%; padding: 0 10px; font-size: 13px; background: #fff; box-sizing: border-box;">
                <option value="">Pilih...</option>
                <option value="">Semua Karyawan</option>
                @foreach($karyawanOptions as $k)
                  <option value="{{ $k->att_nik }}" {{ $str_att_nik_nama == $k->att_nik ? 'selected' : '' }}>
                    {{ $k->nama }} - {{ $k->att_nik }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Column 2: Action, Status, Type -->
            <div style="display: flex; flex-direction: column; gap: 14px;">
              <!-- Action -->
              <div style="background: #ffffff; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Action</span>
                  <label style="font-size: 12px; color: #64748b; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="checkbox" onchange="toggleCheck('att_type[]', this)" style="accent-color: #f05a00; cursor: pointer;">
                    <span>Check All</span>
                  </label>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; max-height: 100px; overflow-y: auto;">
                  @foreach($all_att_type as $t)
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; margin-bottom: 5px; cursor: pointer;">
                      <input type="checkbox" name="att_type[]" value="{{ $t }}" {{ in_array($t, $arr_att_type) ? 'checked' : '' }} style="accent-color: #f05a00; cursor: pointer;">
                      <span>{{ $t }}</span>
                    </label>
                  @endforeach
                </div>
              </div>

              <!-- Status -->
              <div style="background: #ffffff; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Status</span>
                  <label style="font-size: 12px; color: #64748b; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="checkbox" onchange="toggleCheck('att_status[]', this)" style="accent-color: #f05a00; cursor: pointer;">
                    <span>Check All</span>
                  </label>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; max-height: 115px; overflow-y: auto;">
                  @foreach($all_att_status as $s)
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; margin-bottom: 5px; cursor: pointer;">
                      <input type="checkbox" name="att_status[]" value="{{ $s }}" {{ in_array($s, $arr_att_status) ? 'checked' : '' }} style="accent-color: #f05a00; cursor: pointer;">
                      <span>{{ $s }}</span>
                    </label>
                  @endforeach
                </div>
              </div>

              <!-- Type -->
              <div style="background: #ffffff; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Type</span>
                  <label style="font-size: 12px; color: #64748b; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="checkbox" onchange="toggleCheck('att_data_src[]', this)" style="accent-color: #f05a00; cursor: pointer;">
                    <span>Check All</span>
                  </label>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; max-height: 90px; overflow-y: auto;">
                  @foreach($all_att_data_src as $src)
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; margin-bottom: 5px; cursor: pointer;">
                      <input type="checkbox" name="att_data_src[]" value="{{ $src }}" {{ in_array($src, $arr_att_data_src) ? 'checked' : '' }} style="accent-color: #f05a00; cursor: pointer;">
                      <span>{{ $src }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            </div>

            <!-- Column 3: Working Group -->
            <div style="background: #ffffff; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Working Group</span>
                <label style="font-size: 12px; color: #64748b; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                  <input type="checkbox" onchange="toggleCheck('wg_id[]', this)" style="accent-color: #f05a00; cursor: pointer;">
                  <span>Check All</span>
                </label>
              </div>
              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; max-height: 380px; overflow-y: auto;">
                @foreach($working_groups as $wg)
                  <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; margin-bottom: 6px; cursor: pointer;">
                    <input type="checkbox" name="wg_id[]" value="{{ $wg->id }}" {{ in_array($wg->id, $arr_wg_id) ? 'checked' : '' }} style="accent-color: #f05a00; cursor: pointer;">
                    <span>{{ $wg->wg_name }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Column 4: Company -->
            <div style="background: #ffffff; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Company</span>
                <label style="font-size: 12px; color: #64748b; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                  <input type="checkbox" onchange="toggleCheck('perusahaan[]', this)" style="accent-color: #f05a00; cursor: pointer;">
                  <span>Check All</span>
                </label>
              </div>
              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; max-height: 380px; overflow-y: auto;">
                @foreach($companies as $comp)
                  <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; margin-bottom: 6px; cursor: pointer;">
                    <input type="checkbox" name="perusahaan[]" value="{{ $comp->id }}" {{ in_array($comp->id, $arr_perusahaan) ? 'checked' : '' }} style="accent-color: #f05a00; cursor: pointer;">
                    <span>{{ $comp->nama }}</span>
                  </label>
                @endforeach
              </div>
            </div>

          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 28px; background: #ffffff; border-top: 1px solid #f1f5f9;">
          <div>
            <a href="{{ route('matta.attendance.log') }}" class="btn btn-outline" style="height: 38px; border-radius: 8px; font-size: 13px; padding: 0 16px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
              <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                <path d="M21 3v5h-5"/>
                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                <path d="M8 16H3v5"/>
              </svg>
              <span>Reset Filter</span>
            </a>
          </div>

          <div style="display: flex; gap: 10px; align-items: center;">
            <button class="btn btn-outline" type="button" onclick="closeFilterModal()" style="height: 38px; border-radius: 8px; font-size: 13px; padding: 0 18px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 500; cursor: pointer;">
              Batal
            </button>
            <button class="btn btn-primary" type="submit" style="background: #f05a00; border: none; color: #fff; height: 38px; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 24px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(240, 90, 0, 0.2);">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.3-4.3"/>
              </svg>
              <span>Terapkan Filter</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function toggleCheck(name, source) {
    document.getElementsByName(name).forEach(cb => cb.checked = source.checked);
  }

  function openFilterModal(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    const m = document.getElementById('modal-filter-attendance');
    if (m) {
      m.classList.add('open');
      m.style.setProperty('display', 'flex', 'important');
      m.style.setProperty('opacity', '1', 'important');
      m.style.setProperty('visibility', 'visible', 'important');
      m.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }
    if (window.MATTA && window.MATTA.openModal) {
      try { window.MATTA.openModal('modal-filter-attendance'); } catch (err) {}
    }
  }

  function closeFilterModal(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    const m = document.getElementById('modal-filter-attendance');
    if (m) {
      m.classList.remove('open');
      m.style.setProperty('display', 'none', 'important');
      m.style.setProperty('opacity', '0', 'important');
      m.style.setProperty('visibility', 'hidden', 'important');
      m.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }
    if (window.MATTA && window.MATTA.closeModal) {
      try { window.MATTA.closeModal('modal-filter-attendance'); } catch (err) {}
    }
  }

  // Tutup jika klik backdrop
  document.addEventListener('click', function(e) {
    const m = document.getElementById('modal-filter-attendance');
    if (m && e.target === m) {
      closeFilterModal(e);
    }
  });

  // Tutup tombol ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeFilterModal(e);
    }
  });
</script>
<style>
  #modal-filter-attendance.open {
    display: flex !important;
    opacity: 1 !important;
    visibility: visible !important;
    z-index: 99999 !important;
    pointer-events: auto !important;
  }
  #modal-filter-attendance:not(.open) {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
  }
  @keyframes popIn {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
  }
</style>
@endpush
