@extends('matta.layouts.app', ['activeNav' => 'form-' . $module])

@section('title', $mod['name'])
@section('body_module', $module)

@push('styles')
<style>
.btn-sick-submit {
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
  .btn-sick-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-sick-back {
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
  .btn-sick-back:hover {
    background: #000000 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
  }
</style>
@endpush

@section('content')
<main class="main" id="main">

<nav class="breadcrumb" aria-label="Breadcrumb"><span>Forms</span><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span class="current" aria-current="page" data-crumb-current>Sick Leave</span></nav>
<section class="dt-card" data-datatable data-label="data sick leave" data-file="sick-leave" data-module="sick-leave">
  <div class="dt-head"><div class="dt-title-wrap"><div class="dt-icon"><img src="{{ asset('matta-ui/img/modules/sick-leave.png') }}" alt=""></div><div><h1 class="dt-title">Sick Leave</h1><p class="dt-desc">Kelola dan pantau seluruh pengajuan izin sakit karyawan</p></div></div><div class="dt-actions"><button class="btn btn-module btn-md" type="button" data-modal-open="modal-module"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>Ajukan Izin Sakit</button></div></div>
  <div class="dt-toolbar">
  <label class="dt-length"><span>Tampilkan</span><select class="form-control" data-dt-length aria-label="Jumlah baris"><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50" selected>50</option></select><span>entri</span></label>
  <label class="dt-search"><span class="input-icon"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input class="form-control" type="search" placeholder="Cari data sick leave..." data-dt-search></span></label>
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
<div class="modal-backdrop" id="modal-module" aria-hidden="true" data-module="sick-leave"><form class="modal modal-lg" id="form-module" data-title="Sick Leave" data-prefix="SCK" data-name="izin sakit" role="dialog" aria-modal="true" aria-labelledby="mod-title" novalidate>
  <div class="modal-header"><div class="dt-icon" style="width:40px;height:40px"><img src="{{ asset('matta-ui/img/modules/sick-leave.png') }}" alt="" style="width:28px;height:28px"></div><div><div class="modal-title" id="mod-title">Ajukan Izin Sakit</div><div class="modal-sub">Surat Keterangan Sakit</div></div><button class="icon-btn" type="button" data-modal-close aria-label="Tutup"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button></div>
  <div class="modal-body"><div class="form-grid sick-grid">
    <label class="field"><span class="field-label">Pengaju</span><input class="form-control" value="{{ $u['name'] }} - {{ $u['nip'] }}" readonly tabindex="-1" data-no-i18n></label>
    <span class="field-spacer" aria-hidden="true"></span>
    <label class="field"><span class="field-label">Tanggal Mulai <span class="req">*</span></span><input class="form-control" type="date" name="start" required><span class="field-error">Tanggal mulai wajib diisi.</span></label>
    <label class="field"><span class="field-label">Tanggal Akhir <span class="req">*</span></span><input class="form-control" type="date" name="end" required data-after="start"><span class="field-error">Tanggal akhir wajib diisi dan tidak boleh sebelum tanggal mulai.</span></label>
    <label class="field"><span class="field-label">PIC Pengganti</span><input class="form-control" name="pic" list="pic-list" placeholder="Cari nama rekan kerja" autocomplete="off"><datalist id="pic-list"><option value="Muhammad Rizky"></option><option value="Dewi Kaniawati"></option><option value="Bambang Supriyanto"></option><option value="Hendro Wicaksono"></option><option value="Rina Oktaviani"></option><option value="Agus Pratama"></option></datalist></label>
    <label class="field"><span class="field-label">Telepon Pengaju <span class="req">*</span></span><input class="form-control" type="tel" name="phone" placeholder="08xxxxxxxxxx" inputmode="tel" required><span class="field-error">Telepon wajib diisi.</span></label>
    <label class="field"><span class="field-label">Alasan <span class="req">*</span></span><textarea class="form-control" name="reason" rows="4" placeholder="Tuliskan alasan sakit" required></textarea><span class="field-error">Alasan wajib diisi.</span></label>
    <label class="field"><span class="field-label">Alamat</span><textarea class="form-control" name="address" rows="4" placeholder="Alamat selama izin sakit"></textarea></label>
    <label class="field"><span class="field-label">Keterangan</span><textarea class="form-control" name="note" rows="4" placeholder="Catatan tambahan (opsional)"></textarea></label>
    <label class="field"><span class="field-label">Lampiran</span><span class="field-hint">*Maksimal 2MB dengan format pdf atau gambar (jpg, jpeg dan png)</span><input class="form-control" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" data-max-mb="2" style="padding:6px 10px;height:auto"><span class="field-error">Ukuran maksimal 2MB dengan format PDF, JPG, JPEG atau PNG.</span></label>
  </div></div>
  <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
    <button class="btn btn-sick-back" type="button" data-modal-close>
      <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg> Back
    </button>
    <button class="btn btn-sick-submit" type="submit">
      <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> <span data-i18n="Kirim Pengajuan">Send Request</span>
    </button>
  </div>
</form></div>

@endsection
