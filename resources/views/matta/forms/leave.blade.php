@extends('matta.layouts.app', ['activeNav' => 'form-' . $module])

@section('title', $mod['name'])
@section('body_module', $module)

@push('styles')
<style>
.leave-modal {
    max-width: 920px !important;
    width: min(920px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .leave-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .leave-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .leave-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: flex-start;
  }
  .leave-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }
  .leave-quota-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .leave-quota-label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    letter-spacing: .2px;
  }
  .leave-quota-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin: 2px 0 6px;
  }
  .btn-leave-detail {
    background: #64748b !important;
    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    height: 28px !important;
    padding: 0 14px !important;
    border-radius: 6px !important;
    border: none !important;
    align-self: flex-start !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-leave-detail:hover {
    background: #475569 !important;
    transform: translateY(-1px) !important;
  }
  .btn-leave-submit {
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
  .btn-leave-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-leave-preview {
    background: #ff6000 !important;
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
    box-shadow: 0 2px 8px rgba(255, 96, 0, 0.35) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-leave-preview:hover {
    background: #ea4c00 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(255, 96, 0, 0.4) !important;
  }
  .btn-leave-back {
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
  .btn-leave-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
  @media (max-width: 768px) {
    .leave-grid-2,
    .leave-grid-3 {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<main class="main" id="main">

<nav class="breadcrumb" aria-label="Breadcrumb"><span>Forms</span><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span class="current" aria-current="page" data-crumb-current>Leave</span></nav>
<section class="dt-card" data-datatable data-label="data leave" data-file="leave" data-module="leave">
  <div class="dt-head"><div class="dt-title-wrap"><div class="dt-icon"><img src="{{ asset('matta-ui/img/modules/leave.png') }}" alt=""></div><div><h1 class="dt-title">Leave</h1><p class="dt-desc">Kelola dan pantau seluruh pengajuan cuti karyawan</p></div></div><div class="dt-actions"><button class="btn btn-module btn-md" type="button" data-modal-open="modal-module"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>Ajukan Cuti</button></div></div>
  <div class="dt-toolbar">
  <label class="dt-length"><span>Tampilkan</span><select class="form-control" data-dt-length aria-label="Jumlah baris"><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50" selected>50</option></select><span>entri</span></label>
  <label class="dt-search"><span class="input-icon"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input class="form-control" type="search" placeholder="Cari data leave..." data-dt-search></span></label>
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
        <tr data-detail='{{ $detailJson }}'>
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
  .leave-modal {
    max-width: 920px !important;
    width: min(920px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .leave-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .leave-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .leave-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: flex-start;
  }
  .leave-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }
  .leave-quota-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .leave-quota-label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    letter-spacing: .2px;
  }
  .leave-quota-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin: 2px 0 6px;
  }
  .btn-leave-detail {
    background: #64748b !important;
    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    height: 28px !important;
    padding: 0 14px !important;
    border-radius: 6px !important;
    border: none !important;
    align-self: flex-start !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-leave-detail:hover {
    background: #475569 !important;
    transform: translateY(-1px) !important;
  }
  .btn-leave-submit {
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
  .btn-leave-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-leave-preview {
    background: #ff6000 !important;
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
    box-shadow: 0 2px 8px rgba(255, 96, 0, 0.35) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-leave-preview:hover {
    background: #ea4c00 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(255, 96, 0, 0.4) !important;
  }
  .btn-leave-back {
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
  .btn-leave-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
  @media (max-width: 768px) {
    .leave-grid-2,
    .leave-grid-3 {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="modal-backdrop" id="modal-module" aria-hidden="true" data-module="leave">
  <form class="modal leave-modal" id="form-module" data-title="Leave" data-prefix="LVE" data-name="cuti" role="dialog" aria-modal="true" aria-labelledby="mod-title" novalidate>
    <div class="modal-header">
      <div class="dt-icon" style="width:40px;height:40px">
        <img src="{{ asset('matta-ui/img/modules/leave.png') }}" alt="" style="width:28px;height:28px">
      </div>
      <div>
        <div class="modal-title" id="mod-title">Ajukan Cuti</div>
        <div class="modal-sub">Formulir Pengajuan Cuti Karyawan</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>

    <!-- Hidden file input untuk kompatibilitas script app.js -->
    <input type="file" name="file" class="hidden" style="display:none">

    <div class="modal-body leave-body">
      <div class="leave-card">
        <!-- 1. Pengaju -->
        <label class="field">
          <span class="field-label">Pengaju</span>
          <input class="form-control" name="applicant" value="{{ $u['name'] }} - {{ $u['nip'] }}" readonly style="background: #f1f5f9; color: #1e293b; font-weight: 600; cursor: default;" data-leave-applicant>
        </label>

        <!-- 2. Sisa Kuota Cuti & Tipe Cuti -->
        <div class="leave-grid-2">
          <div class="leave-quota-box">
            <span class="leave-quota-label">Sisa Jumlah Hari Cuti Tahunan</span>
            <div class="leave-quota-val">2026: 8 Hari</div>
            <button type="button" class="btn btn-leave-detail" id="btn-quota-detail">Detail</button>
          </div>

          <label class="field">
            <span class="field-label">Tipe Cuti <span class="req">*</span></span>
            <select class="form-control" name="leave_type" required>
              <option value="" disabled selected>Pilih...</option>
              <option value="Cuti Tahunan">Cuti Tahunan</option>
              <option value="Cuti Bersama">Cuti Bersama</option>
              <option value="Cuti Menikah">Cuti Menikah (3 Hari)</option>
              <option value="Cuti Melahirkan">Cuti Melahirkan / Bersalin</option>
              <option value="Cuti Khusus">Cuti Khusus / Alasan Penting</option>
              <option value="Cuti Tanpa Upah">Cuti Di Luar Tanggungan (Unpaid Leave)</option>
            </select>
            <span class="field-error">Tipe cuti wajib dipilih.</span>
          </label>
        </div>

        <!-- 3. Tanggal Mulai, PIC Pengganti, Telepon Pengaju -->
        <div class="leave-grid-3">
          <label class="field">
            <span class="field-label">Tanggal Mulai <span class="req">*</span></span>
            <input class="form-control" type="date" name="start" required placeholder="dd/mm/yyyy">
            <span class="field-error">Tanggal mulai wajib diisi.</span>
          </label>

          <label class="field">
            <span class="field-label">PIC Pengganti</span>
            <input class="form-control" name="pic" list="pic-leave-list" placeholder="Cari nama rekan kerja..." autocomplete="off">
            <datalist id="pic-leave-list">
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

        <!-- 4. Alasan, Alamat Tujuan Cuti, Keterangan -->
        <div class="leave-grid-3">
          <label class="field">
            <span class="field-label">Alasan <span class="req">*</span></span>
            <textarea class="form-control" name="reason" rows="4" placeholder="Tuliskan alasan pengajuan cuti..." required></textarea>
            <span class="field-error">Alasan wajib diisi.</span>
          </label>

          <label class="field">
            <span class="field-label">Alamat Tujuan Cuti</span>
            <textarea class="form-control" name="address" rows="4" placeholder="Alamat atau kota tujuan selama cuti..."></textarea>
          </label>

          <label class="field">
            <span class="field-label">Keterangan</span>
            <textarea class="form-control" name="note" rows="4" placeholder="Catatan tambahan (opsional)..."></textarea>
          </label>
        </div>
      </div>
    </div>

    <!-- Footer Actions: Back di kiri, Preview & Submit di kanan -->
    <div class="modal-footer" style="display: flex; flex-direction: column; align-items: stretch; gap: 14px; padding: 18px 28px 16px; background: #ffffff; border-top: 1.5px solid #e2e8f0;">
      <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <button class="btn btn-leave-back" type="button" data-modal-close>
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg> Back
        </button>
        <div style="display: flex; align-items: center; gap: 10px;">
          <button class="btn btn-leave-preview" type="button" id="btn-leave-preview">
            <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg> Preview
          </button>
          <button class="btn btn-leave-submit" type="submit">
            <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Submit
          </button>
        </div>
      </div>
      <div style="text-align: center; color: #64748b; font-size: 12px; font-weight: 500; padding-top: 6px; border-top: 1px solid #f1f5f9;">
        Copyright 2026 &copy; PT ALITA PRAYA MITRA
      </div>
    </div>
  </form>
</div>

<!-- Modal Pop-up: Detail Kuota Cuti -->
<div class="modal-backdrop" id="modal-quota-detail" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="quota-title" style="max-width: 480px; border-radius: 16px;">
    <div class="modal-header">
      <div class="dt-icon" style="width:36px;height:36px;background:#eff6ff;border-color:#bfdbfe;color:#2563eb;">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
      </div>
      <div>
        <div class="modal-title" id="quota-title">Rincian Kuota Cuti Tahunan</div>
        <div class="modal-sub">Periode Tahun 2026</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
    </div>
    <div class="modal-body" style="padding: 20px 24px;">
      <table style="width: 100%; font-size: 13.5px; border-collapse: collapse;">
        <tbody>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 10px 0; color: #64748b;">Hak Cuti Tahunan 2026</td>
            <td style="padding: 10px 0; text-align: right; font-weight: 700; color: #0f172a;">12 Hari</td>
          </tr>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 10px 0; color: #64748b;">Cuti Terpakai</td>
            <td style="padding: 10px 0; text-align: right; font-weight: 700; color: #ef4444;">-4 Hari</td>
          </tr>
          <tr style="border-bottom: 1.5px solid #e2e8f0; background: #f8fafc;">
            <td style="padding: 12px 8px; font-weight: 700; color: #0f172a;">Sisa Saldo Cuti</td>
            <td style="padding: 12px 8px; text-align: right; font-weight: 800; color: #16a34a; font-size: 16px;">8 Hari</td>
          </tr>
          <tr>
            <td style="padding: 10px 0; color: #64748b; font-size: 12px;">Masa Berlaku</td>
            <td style="padding: 10px 0; text-align: right; font-size: 12px; color: #475569;">s/d 31 Desember 2026</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="modal-footer" style="padding: 12px 24px; background: #ffffff;">
      <button class="btn btn-outline" type="button" data-modal-close style="margin-left: auto;">Tutup</button>
    </div>
  </div>
</div>

<!-- Modal Pop-up: Preview Pengajuan Cuti -->
<div class="modal-backdrop" id="modal-preview-leave" aria-hidden="true">
  <div class="modal modal-lg" role="dialog" aria-modal="true" aria-labelledby="prev-title" style="max-width: 580px; border-radius: 16px;">
    <div class="modal-header">
      <div class="dt-icon" style="width:36px;height:36px;background:#faf5ff;border-color:#e9d5ff;color:#7c3aed;">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div>
        <div class="modal-title" id="prev-title">Pratinjau Pengajuan Cuti</div>
        <div class="modal-sub">Periksa kembali data pengajuan sebelum dikirim</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
    </div>
    <div class="modal-body" style="padding: 22px 24px; font-size: 13.5px;" id="preview-leave-body">
      <!-- Diisi dinamis via JS -->
    </div>
    <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 14px 24px; background: #ffffff;">
      <button class="btn btn-outline" type="button" data-modal-close>Tutup</button>
      <button class="btn btn-leave-submit" type="button" id="btn-preview-confirm">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Konfirmasi &amp; Kirim
      </button>
    </div>
  </div>
</div>


@endsection
