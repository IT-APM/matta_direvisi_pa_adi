@extends('matta.layouts.app', ['activeNav' => 'form-' . $module])

@section('title', $mod['name'])
@section('body_module', $module)

@push('styles')
<style>
.permit-modal {
    max-width: 680px !important;
    width: min(680px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .permit-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .permit-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .permit-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }
  .btn-permit-submit {
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
  .btn-permit-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-permit-back {
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
  .btn-permit-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
  @media (max-width: 600px) {
    .permit-grid-2 {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<main class="main" id="main">

<nav class="breadcrumb" aria-label="Breadcrumb"><span>Forms</span><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span class="current" aria-current="page" data-crumb-current>Permit</span></nav>
<section class="dt-card" data-datatable data-label="data permit" data-file="permit" data-module="permit">
  <div class="dt-head"><div class="dt-title-wrap"><div class="dt-icon"><img src="{{ asset('matta-ui/img/modules/permit.png') }}" alt=""></div><div><h1 class="dt-title">Permit</h1><p class="dt-desc">Kelola dan pantau seluruh pengajuan izin karyawan</p></div></div><div class="dt-actions"><button class="btn btn-module btn-md" type="button" data-modal-open="modal-module"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>Ajukan Izin</button></div></div>
  <div class="dt-toolbar">
  <label class="dt-length"><span>Tampilkan</span><select class="form-control" data-dt-length aria-label="Jumlah baris"><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50" selected>50</option></select><span>entri</span></label>
  <label class="dt-search"><span class="input-icon"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input class="form-control" type="search" placeholder="Cari data permit..." data-dt-search></span></label>
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
  .permit-modal {
    max-width: 680px !important;
    width: min(680px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .permit-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .permit-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .permit-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }
  .btn-permit-submit {
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
  .btn-permit-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-permit-back {
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
  .btn-permit-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
  @media (max-width: 600px) {
    .permit-grid-2 {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="modal-backdrop" id="modal-module" aria-hidden="true" data-module="permit">
  <form class="modal permit-modal" id="form-module" data-title="Permit" data-prefix="PRM" data-name="izin" role="dialog" aria-modal="true" aria-labelledby="mod-title" novalidate>
    <div class="modal-header">
      <div class="dt-icon" style="width:40px;height:40px">
        <img src="{{ asset('matta-ui/img/modules/permit.png') }}" alt="" style="width:28px;height:28px">
      </div>
      <div>
        <div class="modal-title" id="mod-title">Ajukan Izin</div>
        <div class="modal-sub">Formulir Pengajuan Izin Karyawan</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>

    <!-- Hidden file input untuk kompatibilitas script app.js -->
    <input type="file" name="file" class="hidden" style="display:none">

    <div class="modal-body permit-body">
      <div class="permit-card">
        <!-- Row 1: Tipe Izin & Tanggal -->
        <div class="permit-grid-2">
          <label class="field">
            <span class="field-label">Tipe Izin <span class="req">*</span></span>
            <select class="form-control" name="permit_type" required>
              <option value="" disabled selected>Pilih...</option>
              <option value="Izin Datang Terlambat">Izin Datang Terlambat</option>
              <option value="Izin Pulang Awal">Izin Pulang Awal</option>
              <option value="Izin Keperluan Pribadi">Izin Keperluan Pribadi</option>
              <option value="Izin Urusan Perbankan">Izin Urusan Perbankan</option>
              <option value="Izin Check-up Medis">Izin Check-up Medis</option>
              <option value="Izin Meninggalkan Kantor Sementara">Izin Meninggalkan Kantor Sementara</option>
              <option value="Lainnya">Lainnya</option>
            </select>
            <span class="field-error">Tipe izin wajib dipilih.</span>
          </label>

          <label class="field">
            <span class="field-label">Tanggal <span class="req">*</span></span>
            <input class="form-control" type="date" name="start" required placeholder="dd/mm/yyyy">
            <span class="field-error">Tanggal wajib diisi.</span>
          </label>
        </div>

        <!-- Row 2: Alasan -->
        <label class="field">
          <span class="field-label">Alasan <span class="req">*</span></span>
          <textarea class="form-control" name="note" rows="5" placeholder="Tuliskan alasan pengajuan izin..." required style="min-height: 120px;"></textarea>
          <span class="field-error">Alasan wajib diisi.</span>
        </label>
      </div>
    </div>

    <!-- Footer Actions: Back di kiri, Submit di kanan -->
    <div class="modal-footer" style="display: flex; flex-direction: column; align-items: stretch; gap: 14px; padding: 18px 28px 16px; background: #ffffff; border-top: 1.5px solid #e2e8f0;">
      <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <button class="btn btn-permit-back" type="button" data-modal-close>
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg> Back
        </button>
        <button class="btn btn-permit-submit" type="submit">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Submit
        </button>
      </div>
      <div style="text-align: center; color: #64748b; font-size: 12px; font-weight: 500; padding-top: 6px; border-top: 1px solid #f1f5f9;">
        Copyright 2026 &copy; PT ALITA PRAYA MITRA
      </div>
    </div>
  </form>
</div>

@endsection
