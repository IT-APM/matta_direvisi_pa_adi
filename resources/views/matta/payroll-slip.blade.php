@extends('matta.layouts.app', ['activeNav' => 'payroll'])

@section('title', 'Payroll Slip')

@section('content')
<main class="main" id="main" data-page="payroll" style="padding: 24px 32px 48px;">
  <!-- Breadcrumb -->
  <div class="page-top" style="margin-bottom: 20px;">
    <nav class="breadcrumb" aria-label="Breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280;">
      <a href="{{ route('matta.dashboard') }}" style="color: #6b7280; text-decoration: none;">Home</a>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><path d="m9 18 6-6-6-6"/></svg>
      <span style="color: #6b7280;">Payroll</span>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><path d="m9 18 6-6-6-6"/></svg>
      <span class="current" aria-current="page" style="color: #f05a00; font-weight: 600;">Payroll Slip</span>
    </nav>
  </div>

  <!-- Main Data Card -->
  <section class="dt-card" data-datatable data-label="payslips" data-file="payroll-slip" data-page="payroll" data-empty-message="Tidak ada data slip gaji pada periode ini." data-info-english="true" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); overflow: hidden;">
    <!-- Header -->
    <div class="dt-head" style="display: flex; align-items: center; justify-content: space-between; padding: 24px 28px; border-bottom: 1px solid #f1f5f9; gap: 16px; flex-wrap: wrap;">
      <div class="dt-title-wrap" style="display: flex; align-items: center; gap: 16px;">
        <div class="dt-icon" style="width: 48px; height: 48px; border-radius: 12px; background: #FFF9F1; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #f05a00; flex-shrink: 0;">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" width="24" height="24" fill="none" stroke="#f05a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="20" height="15" x="2" y="4.5" rx="2.5"/>
            <circle cx="7.5" cy="12" r="2.2"/>
            <line x1="12" y1="10" x2="18" y2="10"/>
            <line x1="12" y1="14" x2="16" y2="14"/>
          </svg>
        </div>
        <div>
          <h1 class="dt-title" style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 4px 0; line-height: 1.2;">Payroll Slip</h1>
          <p class="dt-desc" style="font-size: 13px; color: #9ca3af; margin: 0;">Manage, review, and download official employee payslips</p>
        </div>
      </div>

      <div class="dt-actions">
        <button class="btn btn-primary" type="button" data-toggle-target="payroll-filters" aria-expanded="false" style="background: #f05a00; border: none; color: #ffffff; border-radius: 10px; padding: 10px 22px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(240, 90, 0, 0.2);">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
          </svg>
          <span>Filter Periode</span>
        </button>
      </div>
    </div>

    <!-- Filter Periode Panel (Toggled by Button) -->
    <div class="dt-filters hidden" id="payroll-filters" style="padding: 18px 28px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 16px; align-items: flex-end;">
      <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
        <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Tahun</span>
        <select class="form-control" data-dt-filter="year" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px;">
          <option value="">Semua Tahun</option>
          @foreach($availableYears as $yr)
            <option value="{{ $yr }}">{{ $yr }}</option>
          @endforeach
        </select>
      </label>

      <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
        <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Bulan</span>
        <select class="form-control" data-dt-filter="month" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px;">
          <option value="">Semua Bulan</option>
          @foreach($monthsList as $m)
            <option value="{{ $m }}">{{ $m }}</option>
          @endforeach
        </select>
      </label>

      <div style="display: flex; gap: 8px; align-items: center;">
        <button class="btn btn-outline" type="button" id="btn-reset-filters" style="height: 38px; border-radius: 8px; font-size: 13px; padding: 0 16px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 500; cursor: pointer;">Reset Filter</button>
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

      <!-- Search payslips -->
      <label class="dt-search" style="margin: 0 auto 0 20px; flex: 0 1 320px; min-width: 220px;">
        <span class="input-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; pointer-events: none;">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
          </svg>
          <input class="form-control" type="search" placeholder="Search payslips..." data-dt-search style="padding: 8px 14px 8px 36px; border-radius: 10px; background: #f9fafb; border: 1px solid #e5e7eb; height: 36px; width: 100%; font-size: 13px; outline: none; box-sizing: border-box;">
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
    <div class="table-wrap" style="margin: 0 28px 24px; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; background: #fff;">
      <table class="dt" style="width: 100%; border-collapse: collapse; font-size: 13px;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 1px solid #e5e7eb;">
            <th class="num" data-sort="num" style="width: 70px; text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none;">
              NO
            </th>
            <th data-sort="num" style="text-align: left; padding: 14px 18px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none;">
              YEAR
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 18px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none;">
              MONTH
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 18px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none;">
              NAME
            </th>
            <th data-sort="text" style="text-align: left; padding: 14px 18px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none;">
              NIP
            </th>
            <th class="center" data-noexport style="text-align: center; padding: 14px 18px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #6b7280; user-select: none;">
              PAYSLIP
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse($slips as $i => $s)
            @php
              $detailObj = [
                'eyebrow' => 'Payroll Slip',
                'title' => 'Slip Gaji ' . $s['month'] . ' ' . $s['year'],
                'icon' => 'doc',
                'badges' => [['Paid', 'green']],
                'person' => ['name' => $s['name'] ?? $u['name'], 'nip' => $s['nip'] ?? $u['nip'], 'role' => $u['role']],
                'sections' => [
                  [
                    'h' => 'Rincian Penerimaan & Potongan',
                    'rows' => [
                      ['Periode', $s['month'] . ' ' . $s['year']],
                      ['Tanggal Pembayaran', $s['paid_at']],
                      ['Gaji Pokok', $s['salary_fmt']],
                      ['Total Tunjangan & Lembur', $s['allowance_fmt']],
                      ['Potongan Pajak & BPJS', $s['deduction_fmt']],
                      ['Take Home Pay (THP)', $s['thp_fmt']],
                    ]
                  ]
                ]
              ];
            @endphp
            <tr data-year="{{ $s['year'] }}" data-month="{{ $s['month'] }}" data-salary="{{ $s['salary_fmt'] }}" data-thp="{{ $s['thp_fmt'] }}" data-detail='@json($detailObj)' style="border-bottom: 1px solid #f1f5f9;">
              <td class="num" data-dt-no style="text-align: center; padding: 14px 16px; color: #9ca3af;">{{ $i + 1 }}</td>
              <td class="strong" style="padding: 14px 18px; font-weight: 600; color: #1e293b;">{{ $s['year'] }}</td>
              <td class="strong" style="padding: 14px 18px; font-weight: 600; color: #1e293b;">{{ $s['month'] }}</td>
              <td style="padding: 14px 18px; color: #334155;">{{ $s['name'] ?? $u['name'] }}</td>
              <td class="mono" style="padding: 14px 18px; font-family: monospace; color: #64748b;">{{ $s['nip'] ?? $u['nip'] }}</td>
              <td class="center" style="text-align: center; padding: 14px 18px;">
                <div class="cell-actions" style="display: inline-flex; align-items: center; gap: 6px; justify-content: center;">
                  @if(!empty($s['pdf_url']))
                    <a href="{{ $s['pdf_url'] }}" target="_blank" class="btn btn-outline btn-xs" style="border-radius: 6px; font-size: 12px; padding: 4px 10px; color: #f05a00; border-color: #fed7aa; background: #fff9f1; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;" title="Unduh / Buka PDF">
                      <svg class="icon icon-xs" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                      PDF
                    </a>
                  @endif
                  <button class="btn btn-outline btn-xs" type="button" data-slip-view style="border-radius: 6px; font-size: 12px; padding: 4px 10px; cursor: pointer;" title="Lihat Detail Slip">Lihat</button>
                </div>
              </td>
            </tr>
          @empty
            <tr class="dt-empty">
              <td colspan="6" style="text-align: center; padding: 48px 16px; color: #94a3b8; font-size: 14px; background: #ffffff;">
                Tidak ada data slip gaji pada periode ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="dt-footer" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 28px 24px; flex-wrap: wrap; gap: 12px;">
      <div class="dt-info" data-dt-info style="font-size: 13px; color: #6b7280;">
        @if(count($slips) > 0)
          Showing <b>1 - {{ count($slips) }}</b> of <b>{{ count($slips) }}</b> payslips
        @else
          Showing <b>0</b> of <b>0</b> payslips
        @endif
      </div>
      <div class="pager" data-dt-pager style="display: flex; align-items: center; gap: 6px;">
        <button type="button" data-p="prev" disabled style="min-width: 32px; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #9ca3af; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: not-allowed;">‹ Previous</button>
        <button type="button" class="active" aria-current="page" style="min-width: 32px; height: 32px; padding: 0 10px; border-radius: 8px; border: 1.5px solid #f05a00; color: #f05a00; background: #fff; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; justify-content: center;">1</button>
        <button type="button" data-p="next" disabled style="min-width: 32px; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #9ca3af; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: not-allowed;">Next ›</button>
      </div>
    </div>
  </section>
</main>
@endsection

@section('modals')
@php
  $latestSlip = $slips[0] ?? [
    'month' => 'Oktober', 'year' => '2026', 'paid_at' => '25 Okt 2026',
    'salary_fmt' => 'Rp 8.500.000', 'allowance_fmt' => 'Rp 2.350.000',
    'deduction_fmt' => 'Rp 650.000', 'thp_fmt' => 'Rp 10.200.000'
  ];
@endphp
<div class="modal-backdrop" id="modal-slip" aria-hidden="true">
  <div class="modal modal-lg" role="dialog" aria-modal="true" aria-labelledby="slip-title">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="slip-title">Slip Gaji Karyawan — <span data-slip-period>{{ $latestSlip['month'] }} {{ $latestSlip['year'] }}</span></div>
        <div class="modal-sub">{{ $u['company'] }} · Dokumen Rahasia (Confidential)</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>
    <div class="modal-body" style="display:grid;gap:16px">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:14px;background:var(--slate-50);border-radius:10px;border:1px solid var(--slate-200);font-size:13px">
        <div><span class="muted">Nama Karyawan:</span> <b>{{ $u['name'] }}</b></div>
        <div><span class="muted">NIP:</span> <b class="mono">{{ $u['nip'] }}</b></div>
        <div><span class="muted">Jabatan:</span> <b>{{ $u['role'] }}</b></div>
        <div><span class="muted">Departemen:</span> <b>{{ $u['department'] }}</b></div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="card" style="padding:14px">
          <h3 style="font-size:13px;margin:0 0 10px;color:var(--green-700)">Penerimaan (Earnings)</h3>
          <dl class="dr-kv">
            <dt>Gaji Pokok</dt><dd class="mono" data-slip-salary>{{ $latestSlip['salary_fmt'] }}</dd>
            <dt>Tunjangan &amp; Lembur</dt><dd class="mono">{{ $latestSlip['allowance_fmt'] }}</dd>
          </dl>
        </div>
        <div class="card" style="padding:14px">
          <h3 style="font-size:13px;margin:0 0 10px;color:var(--red-600)">Potongan (Deductions)</h3>
          <dl class="dr-kv">
            <dt>PPh 21 &amp; BPJS</dt><dd class="mono">{{ $latestSlip['deduction_fmt'] }}</dd>
          </dl>
        </div>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;padding:16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px">
        <span style="font-weight:700;color:#1e3a8a">TAKE HOME PAY (THP)</span>
        <span class="mono" style="font-size:18px;font-weight:700;color:#1d4ed8" data-slip-thp>{{ $latestSlip['thp_fmt'] }}</span>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" type="button" data-modal-close>Tutup</button>
      <button class="btn btn-primary" type="button" data-slip-print>
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
        Cetak / Simpan PDF
      </button>
    </div>
  </div>
</div>
@endsection
