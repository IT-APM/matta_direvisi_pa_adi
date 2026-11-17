@extends('matta.layouts.app', ['activeNav' => 'propose'])

@section('title', 'Overtime Propose')

@section('content')
<main class="main" id="main" data-page="propose" style="padding: 24px 32px 48px;">
  <!-- Breadcrumb -->
  <div class="page-top" style="margin-bottom: 20px;">
    <nav class="breadcrumb" aria-label="Breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280;">
      <a href="{{ route('matta.dashboard') }}" style="color: #6b7280; text-decoration: none;">Home</a>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><path d="m9 18 6-6-6-6"/></svg>
      <span style="color: #6b7280;">Overtime</span>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><path d="m9 18 6-6-6-6"/></svg>
      <span class="current" aria-current="page" style="color: #f05a00; font-weight: 600;">Overtime Propose</span>
    </nav>
  </div>

  <!-- Main Data Card -->
  <section class="dt-card" id="table-propose" data-datatable data-label="proposals" data-file="data-overtime" data-page="propose" data-empty-message="No data available in table" data-info-english="true" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); overflow: hidden;">
    <!-- Header -->
    <div class="dt-head" style="display: flex; align-items: center; justify-content: space-between; padding: 24px 28px; border-bottom: 1px solid #f1f5f9; gap: 16px; flex-wrap: wrap;">
      <div class="dt-title-wrap" style="display: flex; align-items: center; gap: 16px;">
        <div class="dt-icon" style="width: 48px; height: 48px; border-radius: 12px; background: #FFF9F1; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #f05a00; flex-shrink: 0;">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 14.5 14.5"/>
          </svg>
        </div>
        <div>
          <h1 class="dt-title" style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 4px 0; line-height: 1.2;">Data Overtime</h1>
          <p class="dt-desc" style="font-size: 13px; color: #9ca3af; margin: 0;">Manage, propose, and track official employee overtime proposals</p>
        </div>
      </div>

      <div class="dt-actions" style="display: flex; align-items: center; gap: 10px;">
        <button class="btn btn-primary" id="btn-add-new-propose" type="button" onclick="openPesanModal()" data-modal-open="modal-pesan-konfirmasi" style="background: #f05a00; border: none; color: #ffffff; border-radius: 10px; padding: 10px 22px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(240, 90, 0, 0.2); transition: all 0.2s ease;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"/>
            <path d="M12 5v14"/>
          </svg>
          <span>Add new</span>
        </button>

        <span style="color: #cbd5e1; font-size: 18px;">|</span>

        <button class="btn btn-module" type="button" data-toggle-target="propose-filters" aria-expanded="false" style="background: #FFF9F1; border: 1.5px solid #f05a00; color: #f05a00; border-radius: 10px; padding: 9px 20px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 1px 3px rgba(240, 90, 0, 0.08); transition: all 0.2s ease;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
          </svg>
          <span>Filter</span>
        </button>
      </div>
    </div>

    <!-- Filter Panel (Toggled by Button) -->
    <div class="dt-filters hidden" id="propose-filters" style="padding: 18px 28px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
      <form action="{{ route('matta.propose') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 16px; align-items: flex-end;">
        <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
          <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Start Date From</span>
          <input class="form-control" type="date" name="date_from" value="{{ $date_from }}" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px; box-sizing: border-box;">
        </label>

        <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
          <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Start Date To</span>
          <input class="form-control" type="date" name="date_to" value="{{ $date_to }}" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px; box-sizing: border-box;">
        </label>

        <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
          <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</span>
          <select class="form-control" name="status" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px;">
            <option value="">Semua Status</option>
            <option value="Approve" {{ $filter_status === 'Approve' ? 'selected' : '' }}>Approve</option>
            <option value="Waiting" {{ $filter_status === 'Waiting' ? 'selected' : '' }}>Waiting</option>
            <option value="Reject" {{ $filter_status === 'Reject' ? 'selected' : '' }}>Reject</option>
          </select>
        </label>

        <div style="display: flex; gap: 8px; align-items: center;">
          <button class="btn btn-primary" type="submit" style="background: #f05a00; border: none; color: #fff; height: 38px; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 18px; cursor: pointer;">
            Terapkan
          </button>
          <a href="{{ route('matta.propose') }}" class="btn btn-outline" style="height: 38px; border-radius: 8px; font-size: 13px; padding: 0 16px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
            Reset
          </a>
        </div>
      </form>
    </div>

    <!-- Toolbar -->
    <div class="dt-toolbar" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 28px 16px; flex-wrap: wrap; gap: 16px;">
      <!-- Show entries -->
      <label class="dt-length" style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280; margin: 0; flex-shrink: 0;">
        <span>Show</span>
        <select class="form-control" data-dt-length aria-label="Jumlah baris" style="width: 70px; height: 36px; padding: 0 20px 0 10px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 13px; background: #fff;">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50" selected>50</option>
          <option value="100">100</option>
        </select>
        <span>entries</span>
      </label>

      <!-- Export Buttons -->
      <div class="dt-exports" style="display: flex; gap: 6px; flex-wrap: wrap;">
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

      <!-- Search input -->
      <label class="dt-search" style="margin: 0 0 0 auto; flex: 0 1 260px; min-width: 180px;">
        <span class="input-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; pointer-events: none;">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
          </svg>
          <input class="form-control" type="search" placeholder="Search..." data-dt-search style="padding: 8px 14px 8px 36px; border-radius: 10px; background: #f9fafb; border: 1px solid #e5e7eb; height: 36px; width: 100%; font-size: 13px; outline: none; box-sizing: border-box;">
        </span>
      </label>
    </div>

    <!-- Table Wrap -->
    <div class="table-wrap" style="margin: 0 28px 24px; border: 1px solid #e5e7eb; border-radius: 12px; overflow-x: auto; background: #fff;">
      <table class="dt" style="width: 100%; border-collapse: collapse; font-size: 13px; min-width: 1150px;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 1px solid #e5e7eb;">
            <th class="num" data-sort="num" style="width: 50px; text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              NO
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              CREATED AT
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              CREATED BY
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              CREATED NIP
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              APPROVED BY
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              APPROVED NIP
            </th>
            <th class="center" data-sort="text" style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              STATUS
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              START
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              FINISH
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              TITLE
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              TYPE
            </th>
            <th class="center" data-noexport style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none; white-space: nowrap;">
              ACTION
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse($proposals as $i => $p)
            @php
              $detailObj = [
                'eyebrow' => 'Overtime Propose',
                'title' => $p['title'],
                'icon' => 'clock',
                'badges' => [[$p['status_label'], $p['status_color']]],
                'person' => ['name' => $p['created_by'], 'nip' => $p['created_nip'], 'role' => $u['role']],
                'sections' => [
                  [
                    'h' => 'Detail Pengajuan Lembur',
                    'rows' => [
                      ['Created At', $p['created_at']],
                      ['Start', $p['start']],
                      ['Finish', $p['finish']],
                      ['Type', $p['type']],
                      ['Title', $p['title']],
                      ['Deskripsi', $p['desc'] ?: '-'],
                      ['Approved By', $p['approved_by'] . ' (' . $p['approved_nip'] . ')'],
                      ['Status', $p['status_label']],
                    ]
                  ]
                ],
                'timeline' => [
                  ['t' => 'Diajukan', 'by' => $p['created_by'], 'at' => $p['created_at'], 'state' => 'done'],
                  ['t' => 'Persetujuan Atasan', 'by' => $p['approved_by'], 'at' => $p['status_key'] === 'approve' ? $p['start'] : '', 'state' => $p['status_key'] === 'approve' ? 'done' : 'current'],
                ]
              ];
            @endphp
            <tr data-status="{{ $p['status_key'] }}" data-detail='@json($detailObj)' style="border-bottom: 1px solid #f1f5f9;">
              <td class="num" data-dt-no style="text-align: center; padding: 14px 16px; color: #9ca3af; white-space: nowrap;">{{ $i + 1 }}</td>
              <td style="padding: 14px 16px; color: #334155; white-space: nowrap;">{{ $p['created_at'] }}</td>
              <td class="strong" style="padding: 14px 16px; font-weight: 600; color: #1e293b; white-space: nowrap;">{{ $p['created_by'] }}</td>
              <td class="mono" style="padding: 14px 16px; font-family: monospace; color: #64748b; white-space: nowrap;">{{ $p['created_nip'] }}</td>
              <td style="padding: 14px 16px; color: #334155; white-space: nowrap;">{{ $p['approved_by'] }}</td>
              <td class="mono" style="padding: 14px 16px; font-family: monospace; color: #64748b; white-space: nowrap;">{{ $p['approved_nip'] }}</td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                <span class="badge badge-pill badge-{{ $p['status_color'] }}" style="padding: 4px 12px; font-size: 12px; font-weight: 700;">
                  {{ $p['status_label'] }}
                </span>
              </td>
              <td style="padding: 14px 16px; color: #334155; white-space: nowrap;">{{ $p['start'] }}</td>
              <td style="padding: 14px 16px; color: #334155; white-space: nowrap;">{{ $p['finish'] }}</td>
              <td style="padding: 14px 16px; color: #1e293b; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $p['title'] }}">{{ $p['title'] }}</td>
              <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $p['type'] }}</td>
              <td class="center" style="text-align: center; padding: 14px 16px; white-space: nowrap;">
                <button class="btn btn-outline btn-xs" type="button" data-modal-open="drawer-detail" style="border-radius: 6px; font-size: 12px; padding: 4px 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="View Detail">
                  <svg class="icon icon-xs" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  View
                </button>
              </td>
            </tr>
          @empty
            <tr class="dt-empty">
              <td colspan="12" style="text-align: center; padding: 48px 16px; color: #94a3b8; font-size: 14px; background: #ffffff;">
                No data available in table
              </td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr style="background: #f8f9fa; border-top: 1px solid #e5e7eb;">
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280; text-align: center;">No</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Created at</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Created by</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Created NIP</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Approved by</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Approved NIP</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280; text-align: center;">Status</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Start</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Finish</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Title</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280;">Type</th>
            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #6b7280; text-align: center;">Action</th>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Footer -->
    <div class="dt-footer" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 28px 24px; flex-wrap: wrap; gap: 12px;">
      <div class="dt-info" data-dt-info style="font-size: 13px; color: #6b7280;">
        @if(count($proposals) > 0)
          Showing <b>1 - {{ count($proposals) }}</b> of <b>{{ count($proposals) }}</b> entries
        @else
          Showing <b>0</b> to <b>0</b> of <b>0</b> entries
        @endif
      </div>
      <div class="pager" data-dt-pager style="display: flex; align-items: center; gap: 6px;">
        <button type="button" data-p="prev" disabled style="min-width: 32px; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #9ca3af; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: not-allowed;">Previous</button>
        <button type="button" class="active" aria-current="page" style="min-width: 32px; height: 32px; padding: 0 10px; border-radius: 8px; border: 1.5px solid #f05a00; color: #f05a00; background: #fff; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; justify-content: center;">1</button>
        <button type="button" data-p="next" disabled style="min-width: 32px; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #9ca3af; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: not-allowed;">Next</button>
      </div>
    </div>
  </section>

  <!-- Modal 1: Pesan Konfirmasi (Sesuai Gambar 1) -->
  <div class="modal-backdrop" id="modal-pesan-konfirmasi" aria-hidden="true" style="position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(2px); display: none; align-items: center; justify-content: center; padding: 20px;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-pesan-title" style="max-width: 520px; width: 100%; background: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.25); border: 1px solid #e5e7eb; margin: auto;">
      <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid #f1f5f9;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #f59e0b;">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
              <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
            </svg>
          </span>
          <h3 class="modal-title" id="modal-pesan-title" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Pesan</h3>
        </div>
        <button class="icon-btn" type="button" onclick="closePesanModal()" aria-label="Tutup" style="background: none; border: none; cursor: pointer; color: #64748b; padding: 4px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"/>
            <path d="m6 6 12 12"/>
          </svg>
        </button>
      </div>

      <div class="modal-body" style="padding: 22px 24px; font-size: 14.5px; line-height: 1.6; color: #334155;">
        Pastikan anda telah melakukan absensi harian melalui Matta sebelum mengajukan lembur. Silahkan mengganti dengan hari lain jika anda terlewat melakukan absensi harian di Matta
      </div>

      <div class="modal-footer" style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; padding: 14px 24px 20px; border-top: none; background: #ffffff;">
        <button type="button" class="btn" onclick="closePesanModal()" style="background: #f43f5e; color: #ffffff; border: none; border-radius: 6px; padding: 9px 20px; font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(244, 63, 94, 0.2);">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"/>
            <path d="m6 6 12 12"/>
          </svg>
          <span>Batal</span>
        </button>

        <button type="button" class="btn" id="btn-konfirmasi-pesan" onclick="openAddProposeModal()" style="background: #5b5bd6; color: #ffffff; border: none; border-radius: 6px; padding: 9px 22px; font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; box-shadow: 0 2px 6px rgba(91, 91, 214, 0.25);">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span>Konfirmasi</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Modal 2: Pop Up Pengisian (Sesuai Gambar 2) -->
  <div class="modal-backdrop" id="modal-add-propose" aria-hidden="true" style="position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(2px); display: none; align-items: center; justify-content: center; padding: 20px;">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-add-title" style="max-width: 820px; width: 100%; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.25); border: 1px solid #e5e7eb; margin: auto;">
      <form id="form-add-overtime" action="{{ route('matta.propose.submit') }}" method="POST" style="margin: 0;">
        @csrf
        <input type="hidden" name="ot_maker_by" value="{{ auth()->user()->nik ?? $u['nip'] }}">
        
        <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; padding: 22px 28px 14px; border-bottom: none;">
          <h2 id="modal-add-title" style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0;">Add</h2>
          <button class="icon-btn" type="button" onclick="closeAddProposeModal()" aria-label="Tutup" style="background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"/>
              <path d="m6 6 12 12"/>
            </svg>
          </button>
        </div>

        <div class="modal-body" style="padding: 6px 28px 24px;">
          <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px 28px;">
              <!-- Left Column -->
              <div>
                <!-- Maker -->
                <div class="field" style="margin-bottom: 20px;">
                  <label style="display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px;">Maker</label>
                  <div style="font-size: 15px; font-weight: 700; color: #0f172a; line-height: 1.4;">
                    {{ $u['name'] }} / {{ $u['nip'] }}
                  </div>
                </div>

                <!-- Title -->
                <div class="field" style="margin-bottom: 20px;">
                  <label for="ot_title_input" style="display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px;">Title</label>
                  <input type="text" name="ot_title" id="ot_title_input" class="form-control" required style="width: 100%; height: 40px; border-radius: 8px; border: 1px solid #d1d5db; padding: 0 14px; font-size: 14px; background: #fff; box-sizing: border-box; outline: none;">
                </div>

                <!-- Description -->
                <div class="field" style="margin-bottom: 0;">
                  <label for="ot_desc_input" style="display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px;">Description</label>
                  <textarea name="ot_desc" id="ot_desc_input" rows="4" class="form-control" required style="width: 100%; border-radius: 8px; border: 1px solid #d1d5db; padding: 10px 14px; font-size: 14px; background: #fff; font-family: inherit; resize: vertical; box-sizing: border-box; outline: none;"></textarea>
                </div>
              </div>

              <!-- Right Column -->
              <div>
                <!-- Start -->
                <div class="field" style="margin-bottom: 20px;">
                  <label for="ot_start_input" style="display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px;">Start</label>
                  <input type="datetime-local" name="ot_start_at" id="ot_start_input" class="form-control" required style="width: 100%; height: 40px; border-radius: 8px; border: 1px solid #d1d5db; padding: 0 14px; font-size: 14px; background: #fff; box-sizing: border-box; outline: none;">
                </div>

                <!-- Finish -->
                <div class="field" style="margin-bottom: 20px;">
                  <label for="ot_end_input" style="display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px;">Finish</label>
                  <input type="datetime-local" name="ot_end_at" id="ot_end_input" class="form-control" required style="width: 100%; height: 40px; border-radius: 8px; border: 1px solid #d1d5db; padding: 0 14px; font-size: 14px; background: #fff; box-sizing: border-box; outline: none;">
                </div>

                <!-- Type -->
                <div class="field" style="margin-bottom: 0;">
                  <label for="ot_type_input" style="display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px;">Type</label>
                  <select name="ot_type_code" id="ot_type_code_input" class="form-control" required style="width: 100%; height: 40px; border-radius: 8px; border: 1px solid #d1d5db; padding: 0 14px; font-size: 14px; background: #fff; box-sizing: border-box; outline: none;">
                    <option value="" selected disabled>Pilih...</option>
                    @foreach($otTypes as $type)
                      <option value="{{ $type->ot_type }}">{{ $type->ot_type }}</option>
                    @endforeach
                    @if(empty($otTypes) || count($otTypes) === 0)
                      <option value="Hari Kerja">Hari Kerja</option>
                      <option value="Hari Libur">Hari Libur</option>
                    @endif
                  </select>
                </div>
              </div>
            </div>

            <!-- Bottom Actions: Submit & Back (Sesuai Gaya Desain Portal MATTA) -->
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 24px;">
              <button type="submit" class="btn btn-primary" style="background: #f05a00; color: #ffffff; border: none; border-radius: 10px; padding: 10px 26px; font-weight: 600; font-size: 14px; cursor: pointer; box-shadow: 0 2px 6px rgba(240, 90, 0, 0.25); display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 6 9 17l-5-5"/>
                </svg>
                <span>Submit</span>
              </button>

              <button type="button" class="btn btn-outline" onclick="closeAddProposeModal()" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569; border-radius: 10px; padding: 9px 24px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
                <span>Back</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
  function openPesanModal() {
    var m = document.getElementById('modal-pesan-konfirmasi');
    if (m) {
      m.style.display = 'flex';
      m.classList.add('open');
      m.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }
  }

  function closePesanModal() {
    var m = document.getElementById('modal-pesan-konfirmasi');
    if (m) {
      m.style.display = 'none';
      m.classList.remove('open');
      m.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }
  }

  function openAddProposeModal() {
    closePesanModal();
    setTimeout(function() {
      var m = document.getElementById('modal-add-propose');
      if (m) {
        m.style.display = 'flex';
        m.classList.add('open');
        m.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      }
    }, 100);
  }

  function closeAddProposeModal() {
    var m = document.getElementById('modal-add-propose');
    if (m) {
      m.style.display = 'none';
      m.classList.remove('open');
      m.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }
  }

  // Backdrop click closing
  window.addEventListener('click', function(e) {
    if (e.target.id === 'modal-pesan-konfirmasi') closePesanModal();
    if (e.target.id === 'modal-add-propose') closeAddProposeModal();
  });

  // Validation
  document.addEventListener('DOMContentLoaded', function() {
    var formAdd = document.getElementById('form-add-overtime');
    if (formAdd) {
      formAdd.addEventListener('submit', function(e) {
        var start = document.getElementById('ot_start_input').value;
        var end = document.getElementById('ot_end_input').value;
        if (start && end && start >= end) {
          e.preventDefault();
          alert('Waktu finish harus setelah waktu start.');
          return;
        }
      });
    }
  });
</script>
@endsection
