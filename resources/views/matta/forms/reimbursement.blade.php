@extends('matta.layouts.app', ['activeNav' => 'form-' . $module])

@section('title', $mod['name'])
@section('body_module', $module)

@push('styles')
<style>
.rmb-modal {
    max-width: 860px !important;
    width: min(860px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .rmb-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .rmb-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .rmb-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }
  .btn-rmb-submit {
    background: #22c55e !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
    height: 38px !important;
    padding: 0 28px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.35) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-rmb-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-rmb-back {
    background: #111827 !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
    height: 38px !important;
    padding: 0 24px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-rmb-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
  @media (max-width: 680px) {
    .rmb-grid-2 {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<main class="main" id="main">

<nav class="breadcrumb" aria-label="Breadcrumb"><span>Forms</span><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span class="current" aria-current="page" data-crumb-current>Reimbursement</span></nav>
<section class="dt-card" data-datatable data-label="data reimbursement" data-file="reimbursement" data-module="reimbursement">
  <div class="dt-head">
    <div class="dt-title-wrap">
      <div class="dt-icon"><img src="{{ asset('matta-ui/img/modules/reimbursement.png') }}" alt=""></div>
      <div>
        <h1 class="dt-title">Reimbursement</h1>
        <p class="dt-desc">Kelola dan pantau seluruh pengajuan reimbursement karyawan</p>
      </div>
    </div>
    <div class="dt-actions">
      <button class="btn btn-primary" type="button" data-toggle-target="reimbursement-filters" aria-expanded="false" style="background: #f05a00; border: none; color: #ffffff; border-radius: 10px; padding: 10px 22px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(240, 90, 0, 0.2);">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/>
          <path d="m21 21-4.3-4.3"/>
        </svg>
        <span>Filter</span>
      </button>
    </div>
  </div>

  <!-- Filter Panel (Toggled by Button) -->
  <div class="dt-filters hidden" id="reimbursement-filters" style="padding: 18px 28px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 16px; align-items: flex-end;">
    <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
      <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</span>
      <select class="form-control" data-dt-filter="status" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px;">
        <option value="">Semua Status</option>
        <option value="Waiting Approval">Waiting Approval</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
        <option value="Pending">Pending</option>
      </select>
    </label>

    <label class="field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
      <span class="field-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Tahun</span>
      <select class="form-control" data-dt-filter="year" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; width: 100%; padding: 0 12px;">
        <option value="">Semua Tahun</option>
        @php
          $years = collect($mod['rows'])->map(function($r) {
              return !empty($r['date']) ? date('Y', strtotime($r['date'])) : null;
          })->filter()->unique()->sortDesc();
          if ($years->isEmpty()) {
              $years = collect([date('Y'), date('Y') - 1]);
          }
        @endphp
        @foreach($years as $yr)
          <option value="{{ $yr }}">{{ $yr }}</option>
        @endforeach
      </select>
    </label>

    <div style="display: flex; gap: 8px; align-items: center;">
      <button class="btn btn-outline" type="button" data-dt-reset style="height: 38px; border-radius: 8px; font-size: 13px; padding: 0 16px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; font-weight: 600; cursor: pointer;">
        Reset
      </button>
    </div>
  </div>
  <div class="dt-toolbar">
  <label class="dt-length"><span>Tampilkan</span><select class="form-control" data-dt-length aria-label="Jumlah baris"><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50" selected>50</option></select><span>entri</span></label>
  <label class="dt-search"><span class="input-icon"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input class="form-control" type="search" placeholder="Cari data reimbursement..." data-dt-search></span></label>
  <div class="dt-exports">
    <button class="btn btn-outline" type="button" data-dt-export="copy"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>Copy</button>
    <button class="btn btn-outline" type="button" data-dt-export="csv"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>CSV</button>
    <button class="btn btn-outline xls" type="button" data-dt-export="excel"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>Excel</button>
    <button class="btn btn-outline pdf" type="button" data-dt-export="pdf"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>PDF</button>
    <button class="btn btn-outline" type="button" data-dt-export="print"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>Print</button>
  </div>
</div>
  <div class="table-wrap"><table class="dt">
    <thead><tr><th class="num" data-sort="num">No<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th data-sort="text">#ID Formulir<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th data-sort="text">Nama Karyawan<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th data-sort="text">Jabatan<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th data-sort="text">Unit Kerja<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th data-sort="date">Created At<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th class="center" data-sort="text">Status<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th><th class="center" data-noexport>Action</th></tr></thead>
        <tbody>
      @forelse($mod['rows'] as $r)
        @php
          $ini = collect(explode(' ', trim($r['name'] ?? $u['name'])))->filter()->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') ?: 'MD';
          $st = $r['status'] ?? 'Waiting Approval';
          $badge = match($st) {
            'Approved' => 'badge-green',
            'Rejected' => 'badge-red',
            'Pending' => 'badge-amber',
            default => 'badge-orange',
          };
          $dateParts = explode(' ', $r['time'] ?? '');
          $timeStr = $r['time'] ?? '';
          $dateVal = $r['date'] ?? date('Y-m-d');
          $unit = $r['unit'] ?? ($u['department'] ?? 'Alita');
          $unitParts = explode('—', $unit);
          $unit1 = trim($unitParts[0] ?? $unit);
          $unit2 = trim($unitParts[1] ?? ($u['role'] ?? 'Staff'));
          
          $detailJson = json_encode([
            'eyebrow' => $mod['name'],
            'title' => $r['id'] ?? '#FORM',
            'module' => $module,
            'img' => asset('matta-ui/img/modules/' . $module . '.png'),
            'badges' => [[$st, $st === 'Approved' ? 'green' : ($st === 'Rejected' ? 'red' : 'orange')]],
            'person' => ['name' => $r['name'] ?? $u['name'], 'nip' => $r['nip'] ?? $u['nip'], 'role' => $r['role'] ?? $u['role']],
            'sections' => [[
              'h' => 'Detail Pengajuan',
              'rows' => [
                ['ID Formulir', $r['id'] ?? '#FORM'],
                ['Jenis', $r['form'] ?? $mod['name']],
                ['Pengaju', ($r['name'] ?? $u['name']) . ' (' . ($r['nip'] ?? $u['nip']) . ')'],
                ['Tanggal', $r['date'] ?? ''],
                ['Keterangan', $r['time'] ?? ''],
                ['Unit Kerja', $unit]
              ]
            ]],
            'attachment' => $r['file'] ?? '-'
          ]);
        @endphp
        <tr data-detail='{{ $detailJson }}' data-status="{{ $st }}" data-year="{{ !empty($dateVal) ? date('Y', strtotime($dateVal)) : '' }}">
          <td class="num" data-dt-no></td>
          <td><span class="id-chip">{{ $r['id'] ?? '' }}</span></td>
          <td>
            <div class="person">
              <span class="avatar-initial">{{ $ini }}</span>
              <div>
                <div class="person-name">{{ $r['name'] ?? $u['name'] }}</div>
                <div class="person-sub">{{ $r['nip'] ?? $u['nip'] }}</div>
              </div>
            </div>
          </td>
          <td>{{ $r['role'] ?? $u['role'] }}</td>
          <td class="cell-2">
            <div>{{ $unit1 }}</div>
            <div class="sub-line">{{ $unit2 }}</div>
          </td>
          <td class="cell-2" data-value="{{ $dateVal }}">
            <div class="main-line">{{ $dateVal }}</div>
            <div class="sub-line">{{ $timeStr }}</div>
          </td>
          <td class="center"><span class="badge badge-pill {{ $badge }}">{{ $st }}</span></td>
          <td class="center">
            <button class="icon-btn" type="button" data-row-detail data-title="Detail {{ $r['id'] ?? '' }}" aria-label="Lihat detail">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </td>
        </tr>
      @empty
        <tr class="dt-empty">
          <td colspan="8" class="center text-muted" style="padding: 44px 16px; text-align: center; color: #94a3b8;">
            Belum ada pengajuan {{ strtolower($mod['short'] ?? $mod['name']) }}.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table></div>
  <div class="dt-footer"><div class="dt-info" data-dt-info></div><div class="pager" data-dt-pager></div></div><div class="dt-updated right">Terakhir diperbarui: Hari ini, 18:30 WIB</div>
</section>

</main>

<div class="modal-backdrop" id="drawer-detail" aria-hidden="true">
  <div class="modal modal-lg" role="dialog" aria-modal="true" aria-labelledby="dr-title" style="max-width: 640px;">
    <div class="dr-head modal-header">
      <span class="dr-ico" data-dr-ico></span>
      <div class="dr-hgroup"><div class="dr-eyebrow" data-dr-eyebrow>Detail</div><h2 class="dr-title" id="dr-title" data-dr-title>Detail</h2></div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
    </div>
    <div class="dr-body modal-body" data-dr-body></div>
    <div class="dr-foot modal-footer"><button class="btn btn-outline" type="button" data-modal-close>Tutup</button><button class="btn btn-primary" type="button" data-dr-print><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>Cetak</button></div>
    </div>
</div>
<style>
  .rmb-modal {
    max-width: 860px !important;
    width: min(860px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .rmb-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .rmb-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .rmb-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }
  .btn-rmb-submit {
    background: #22c55e !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
    height: 38px !important;
    padding: 0 28px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.35) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-rmb-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-rmb-back {
    background: #111827 !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
    height: 38px !important;
    padding: 0 24px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-rmb-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
  @media (max-width: 680px) {
    .rmb-grid-2 {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="modal-backdrop" id="modal-module" aria-hidden="true" data-module="reimbursement">
  <form class="modal rmb-modal" id="form-module" data-title="Reimbursement" data-prefix="RMB" data-name="reimbursement" role="dialog" aria-modal="true" aria-labelledby="mod-title" novalidate>
    <div class="modal-header">
      <div class="dt-icon" style="width:40px;height:40px">
        <img src="{{ asset('matta-ui/img/modules/reimbursement.png') }}" alt="" style="width:28px;height:28px">
      </div>
      <div>
        <div class="modal-title" id="mod-title">Ajukan Reimbursement</div>
        <div class="modal-sub">Formulir Pengajuan Reimbursement Karyawan</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>

    <div class="modal-body rmb-body">
      <div class="rmb-card">
        <!-- 1. Pengaju -->
        <label class="field">
          <span class="field-label">Pengaju</span>
          <input class="form-control" name="applicant" value="{{ $u['name'] }} - {{ $u['nip'] }}" readonly style="background: #f1f5f9; color: #1e293b; font-weight: 600; cursor: default;" data-rmb-applicant>
        </label>

        <!-- 2. Tanggal Mulai & Tanggal Akhir -->
        <div class="rmb-grid-2">
          <label class="field">
            <span class="field-label">Tanggal Mulai <span class="req">*</span></span>
            <input class="form-control" type="date" name="start" required placeholder="dd/mm/yyyy">
            <span class="field-error">Tanggal mulai wajib diisi.</span>
          </label>

          <label class="field">
            <span class="field-label">Tanggal Akhir <span class="req">*</span></span>
            <input class="form-control" type="date" name="end" required placeholder="dd/mm/yyyy" data-after="start">
            <span class="field-error">Tanggal akhir wajib diisi dan tidak boleh sebelum tanggal mulai.</span>
          </label>
        </div>

        <!-- 3. PIC Pengganti & Telepon Pengaju -->
        <div class="rmb-grid-2">
          <label class="field">
            <span class="field-label">PIC Pengganti</span>
            <input class="form-control" name="pic" list="pic-rmb-list" placeholder="Cari nama rekan kerja..." autocomplete="off">
            <datalist id="pic-rmb-list">
              <option value="Muhammad Rizky - 01031502441"></option>
              <option value="Dewi Kaniawati - 01031603882"></option>
              <option value="Bambang Supriyanto - 01031401290"></option>
              <option value="Hendro Wicaksono - 01031704512"></option>
              <option value="Rina Oktaviani - 01031805934"></option>
            </datalist>
          </label>

          <label class="field">
            <span class="field-label">Telepon Pengaju</span>
            <input class="form-control" type="tel" name="phone" placeholder="08xxxxxxxxxx" inputmode="tel">
          </label>
        </div>

        <!-- 4. Alasan & Alamat -->
        <div class="rmb-grid-2">
          <label class="field">
            <span class="field-label">Alasan <span class="req">*</span></span>
            <textarea class="form-control" name="reason" rows="4" placeholder="Tuliskan alasan / keperluan reimbursement..." required></textarea>
            <span class="field-error">Alasan wajib diisi.</span>
          </label>

          <label class="field">
            <span class="field-label">Alamat</span>
            <textarea class="form-control" name="address" rows="4" placeholder="Alamat atau lokasi kegiatan terkait..."></textarea>
          </label>
        </div>

        <!-- 5. Keterangan & Lampiran -->
        <div class="rmb-grid-2">
          <label class="field">
            <span class="field-label">Keterangan</span>
            <textarea class="form-control" name="note" rows="4" placeholder="Catatan tambahan (opsional)..."></textarea>
          </label>

          <label class="field">
            <span class="field-label">Lampiran</span>
            <span class="field-hint" style="font-size: 11.5px; color: #64748b; margin-bottom: 4px;">*Maksimal 2MB dengan format pdf atau gambar (jpg, jpeg dan png)</span>
            <input class="form-control" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" data-max-mb="2" style="padding:6px 10px;height:auto">
          </label>
        </div>
      </div>
    </div>

    <!-- Footer Actions: Back di kiri, Submit di kanan -->
    <div class="modal-footer" style="display: flex; flex-direction: column; align-items: stretch; gap: 14px; padding: 18px 28px 16px; background: #ffffff; border-top: 1.5px solid #e2e8f0;">
      <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <button class="btn btn-rmb-back" type="button" data-modal-close>
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg> Back
        </button>
        <button class="btn btn-rmb-submit" type="submit">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> <span data-i18n="Kirim Pengajuan">Send Request</span>
        </button>
      </div>
      <div style="text-align: center; color: #64748b; font-size: 12px; font-weight: 500; padding-top: 6px; border-top: 1px solid #f1f5f9;">
        Copyright 2026 &copy; PT ALITA PRAYA MITRA
      </div>
    </div>
  </form>
</div>

@endsection
