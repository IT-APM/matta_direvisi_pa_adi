@extends('matta.layouts.app', ['activeNav' => 'form-' . $module])

@section('title', $mod['name'])
@section('body_module', $module)

@push('styles')
<style>
.exc-modal {
    max-width: 980px !important;
    width: min(980px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .exc-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .exc-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    margin-bottom: 22px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  }
  .exc-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
  }
  .exc-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.2px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
  }
  .exc-card-title-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: inline-grid;
    place-items: center;
    background: #FFF9F1;
    color: #f05a00;
    border: 1px solid #fed7aa;
  }
  .exc-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }
  .exc-row-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 14px;
    transition: border-color .15s, box-shadow .15s;
    background: #fcfcfd;
  }
  .exc-row-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
  }
  .exc-row-top {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 12px;
  }
  .exc-actions-bar {
    display: flex;
    gap: 10px;
    margin-top: 10px;
  }
  .btn-add-row {
    background: #ff6000 !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 600 !important;
    font-size: 12.5px !important;
    height: 32px !important;
    padding: 0 16px !important;
    border-radius: 7px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 1px 3px rgba(255, 96, 0, 0.3) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-add-row:hover {
    background: #ea4c00 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 8px rgba(255, 96, 0, 0.35) !important;
  }
  .btn-del-row {
    background: #ef4444 !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 600 !important;
    font-size: 12.5px !important;
    height: 32px !important;
    padding: 0 16px !important;
    border-radius: 7px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 1px 3px rgba(239, 68, 68, 0.3) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-del-row:hover {
    background: #dc2626 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 8px rgba(239, 68, 68, 0.35) !important;
  }
  .btn-exc-submit {
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
  .btn-exc-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-exc-back {
    background: #64748b !important;
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
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-exc-back:hover {
    background: #475569 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.4) !important;
  }
  @media (max-width: 768px) {
    .exc-grid-3 {
      grid-template-columns: 1fr;
    }
    .exc-row-top {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<main class="main" id="main">

<nav class="breadcrumb" aria-label="Breadcrumb"><span>Forms</span><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span class="current" aria-current="page" data-crumb-current>Exit Clearance</span></nav>
<section class="dt-card" data-datatable data-label="data exit clearance" data-file="exit-clearance" data-module="exit-clearance">
  <div class="dt-head"><div class="dt-title-wrap"><div class="dt-icon"><img src="{{ asset('matta-ui/img/modules/exit-clearance.png') }}" alt=""></div><div><h1 class="dt-title">Exit Clearance</h1><p class="dt-desc">Kelola dan pantau seluruh pengajuan exit clearance karyawan</p></div></div><div class="dt-actions"><button class="btn btn-module btn-md" type="button" data-modal-open="modal-statement"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>Ajukan Exit Clearance</button></div></div>
  <div class="dt-toolbar">
  <label class="dt-length"><span>Tampilkan</span><select class="form-control" data-dt-length aria-label="Jumlah baris"><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50" selected>50</option></select><span>entri</span></label>
  <label class="dt-search"><span class="input-icon"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input class="form-control" type="search" placeholder="Cari data exit clearance..." data-dt-search></span></label>
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
<div class="modal-backdrop" id="modal-statement" aria-hidden="true">
  <div class="modal modal-lg" role="dialog" aria-modal="true" aria-labelledby="statement-title" style="max-width: 740px; width: min(740px, calc(100vw - 32px)); padding: 0; overflow: hidden; border-radius: 14px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);">
    <div class="modal-header" style="justify-content: center; position: relative; padding: 22px 24px 18px; border-bottom: 1.5px solid #e2e8f0; background: #ffffff;">
      <h2 class="modal-title" id="statement-title" style="font-size: 19px; font-weight: 800; color: #0f172a; text-align: center; letter-spacing: 0.3px; margin: 0;">SURAT PERNYATAAN KESANGGUPAN</h2>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup" style="position: absolute; right: 18px; top: 18px;">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding: 24px 30px 20px; max-height: calc(85vh - 145px); overflow-y: auto; color: #334155; font-size: 13.5px; line-height: 1.65; background: #ffffff;">
      @php
        $karyawan = $u['karyawan'] ?? null;
        $stmtName = $u['name'] ?? ($karyawan?->nama ?? '-');
        $stmtPosition = $karyawan?->_jabatan?->jabatan ?? ($u['role'] ?? '-');
        $stmtNip = $u['nip'] ?? ($karyawan?->nik ?? '-');
        $stmtDirectorate = $karyawan?->_divisi?->nama ?? ($u['directorate'] ?? '-');
        $stmtDivision = $karyawan?->_departemen?->nama ?? ($u['department'] ?? '-');
        $stmtDepartment = $karyawan?->_subdepartemen?->nama ?? ($u['sub_department'] ?? '-');
        $stmtCompany = $karyawan?->_perusahaan?->nama ?? ($u['company'] ?? 'PT ALITA PRAYA MITRA');
      @endphp
      <p style="margin: 0 0 12px; color: #334155; font-size: 13.5px;">Yang menyatakan di bawah ini :</p>
      
      <table style="width: 100%; max-width: 480px; border-collapse: collapse; margin: 0 0 16px; font-size: 13.5px;">
        <tbody>
          <tr>
            <td style="width: 130px; padding: 2.5px 0; color: #334155;">Nama</td>
            <td style="width: 16px; padding: 2.5px 0; color: #64748b;">:</td>
            <td style="padding: 2.5px 0; font-weight: 500; color: #0f172a;" data-stmt-name>{{ $stmtName }}</td>
          </tr>
          <tr>
            <td style="padding: 2.5px 0; color: #334155;">Jabatan</td>
            <td style="padding: 2.5px 0; color: #64748b;">:</td>
            <td style="padding: 2.5px 0; font-weight: 500; color: #0f172a;" data-stmt-position>{{ $stmtPosition }}</td>
          </tr>
          <tr>
            <td style="padding: 2.5px 0; color: #334155;">NIP</td>
            <td style="padding: 2.5px 0; color: #64748b;">:</td>
            <td style="padding: 2.5px 0; font-weight: 500; color: #0f172a;" data-stmt-nip>{{ $stmtNip }}</td>
          </tr>
          <tr>
            <td style="padding: 2.5px 0; color: #334155;">Direktorat</td>
            <td style="padding: 2.5px 0; color: #64748b;">:</td>
            <td style="padding: 2.5px 0; font-weight: 500; color: #0f172a;" data-stmt-directorate>{{ $stmtDirectorate }}</td>
          </tr>
          <tr>
            <td style="padding: 2.5px 0; color: #334155;">Divisi</td>
            <td style="padding: 2.5px 0; color: #64748b;">:</td>
            <td style="padding: 2.5px 0; font-weight: 500; color: #0f172a;" data-stmt-division>{{ $stmtDivision }}</td>
          </tr>
          <tr>
            <td style="padding: 2.5px 0; color: #334155;">Departemen</td>
            <td style="padding: 2.5px 0; color: #64748b;">:</td>
            <td style="padding: 2.5px 0; font-weight: 500; color: #0f172a;" data-stmt-department>{{ $stmtDepartment }}</td>
          </tr>
        </tbody>
      </table>

      <p style="margin: 0 0 16px; color: #334155; font-size: 13.5px;">
        Adalah subjek hukum yang terikat sebagai karyawan <strong>{{ $stmtCompany }}</strong>.
      </p>

      <div style="text-align: center; font-weight: 800; text-decoration: underline; font-size: 14px; color: #0f172a; margin: 18px 0 14px; letter-spacing: 0.5px;">
        MENYATAKAN
      </div>

      <p style="margin: 0 0 14px; color: #334155; font-size: 13.5px; line-height: 1.6;">
        Dalam hal berakhirnya hubungan kerja sebagai Karyawan <strong>{{ $stmtCompany }}</strong> tersebut di atas maka, SANGGUP UNTUK :
      </p>

      <div style="margin: 0 0 16px; padding-left: 2px;">
        <div style="display: flex; gap: 8px; margin-bottom: 11px; font-size: 13px; line-height: 1.6;">
          <span style="font-weight: 600; min-width: 18px;">A.</span>
          <div>Menjaga Seluruh Kerahasiaan Pekerjaan yang telah dilakukannya di <strong>{{ $stmtCompany }}</strong>, termasuk di dalamnya hal-hal yang terkait dengan Produk, Harga, Teknologi, Sistem Kerja serta Organisasi, dan tidak memberikan informasi terkait hal-hal tersebut di atas kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</div>
        </div>
        <div style="display: flex; gap: 8px; margin-bottom: 11px; font-size: 13px; line-height: 1.6;">
          <span style="font-weight: 600; min-width: 18px;">B.</span>
          <div>Menyerahkan kembali seluruh Alat Kerja milik <strong>{{ $stmtCompany }}</strong> yang telah diberikan sebagai penunjang pekerjaan, dalam keadaan dan fungsi yang baik, dibuktikan dengan Membuat Berita Acara Serah Terima Alat Kerja;</div>
        </div>
        <div style="display: flex; gap: 8px; margin-bottom: 11px; font-size: 13px; line-height: 1.6;">
          <span style="font-weight: 600; min-width: 18px;">C.</span>
          <div>Menjaga Seluruh Kerahasiaan Data dan Informasi <strong>{{ $stmtCompany }}</strong> atas berakhirnya masa Kerja, dan tidak memberikan informasi terkait hal-hal tersebut kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</div>
        </div>
        <div style="display: flex; gap: 8px; margin-bottom: 11px; font-size: 13px; line-height: 1.6;">
          <span style="font-weight: 600; min-width: 18px;">D.</span>
          <div>Dituntut sesuai hukum yang berlaku, apabila melanggar seluruh ketentuan dalam Surat Pernyataan Kesanggupan ini.</div>
        </div>
      </div>

      <p style="margin: 0; color: #334155; font-size: 13px; line-height: 1.65;">
        Pernyataan ini dibuat secara sadar, tanpa ada tekanan dari pihak manapun dan akan dipatuhi dan dilaksanakan secara penuh tanggung jawab, dan sesuai dengan peraturan Perundangan yang berlaku.
      </p>
    </div>
    <div class="modal-footer" style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding: 14px 26px; border-top: 1.5px solid #e2e8f0; background: #ffffff;">
      <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
      <button type="button" class="btn" id="btn-agree-statement" style="background: #ff6000; border: none; color: #ffffff; font-weight: 700; padding: 9px 30px; border-radius: 7px; font-size: 13.5px; cursor: pointer; box-shadow: 0 2px 6px rgba(255, 152, 0, 0.35);">Setuju</button>
    </div>
  </div>
</div>
<style>
  .exc-modal {
    max-width: 980px !important;
    width: min(980px, calc(100vw - 32px)) !important;
    border-radius: 18px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4) !important;
  }
  .exc-body {
    background: #f8fafc;
    padding: 24px 28px;
    max-height: calc(88vh - 140px);
    overflow-y: auto;
  }
  .exc-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
    margin-bottom: 22px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  }
  .exc-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
  }
  .exc-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.2px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
  }
  .exc-card-title-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: inline-grid;
    place-items: center;
    background: #FFF9F1;
    color: #f05a00;
    border: 1px solid #fed7aa;
  }
  .exc-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }
  .exc-row-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 14px;
    transition: border-color .15s, box-shadow .15s;
    background: #fcfcfd;
  }
  .exc-row-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
  }
  .exc-row-top {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 12px;
  }
  .exc-actions-bar {
    display: flex;
    gap: 10px;
    margin-top: 10px;
  }
  .btn-add-row {
    background: #ff6000 !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 600 !important;
    font-size: 12.5px !important;
    height: 32px !important;
    padding: 0 16px !important;
    border-radius: 7px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 1px 3px rgba(255, 96, 0, 0.3) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-add-row:hover {
    background: #ea4c00 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 8px rgba(255, 96, 0, 0.35) !important;
  }
  .btn-del-row {
    background: #ef4444 !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 600 !important;
    font-size: 12.5px !important;
    height: 32px !important;
    padding: 0 16px !important;
    border-radius: 7px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 1px 3px rgba(239, 68, 68, 0.3) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-del-row:hover {
    background: #dc2626 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 8px rgba(239, 68, 68, 0.35) !important;
  }
  .btn-exc-submit {
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
  .btn-exc-submit:hover {
    background: #16a34a !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
  }
  .btn-exc-back {
    background: #64748b !important;
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
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3) !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
  }
  .btn-exc-back:hover {
    background: #475569 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.4) !important;
  }
  @media (max-width: 768px) {
    .exc-grid-3 {
      grid-template-columns: 1fr;
    }
    .exc-row-top {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="modal-backdrop" id="modal-module" aria-hidden="true" data-module="exit-clearance">
  <form class="modal exc-modal" id="form-module" data-title="Exit Clearance" data-prefix="EXC" data-name="exit clearance" role="dialog" aria-modal="true" aria-labelledby="mod-title" novalidate>
    <div class="modal-header">
      <div class="dt-icon" style="width:40px;height:40px">
        <img src="{{ asset('matta-ui/img/modules/exit-clearance.png') }}" alt="" style="width:28px;height:28px">
      </div>
      <div>
        <div class="modal-title" id="mod-title">Ajukan Exit Clearance</div>
        <div class="modal-sub">Formulir Exit Clearance &amp; Serah Terima Aset Karyawan</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>

    <!-- Hidden file input untuk kompatibilitas app.js -->
    <input type="file" name="file" class="hidden" style="display:none">

    <div class="modal-body exc-body">
      <!-- Section 1: Karyawan -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            Karyawan
          </h3>
        </div>
        <div class="exc-grid-3">
          <label class="field">
            <span class="field-label">Alasan Non Aktif <span class="req">*</span></span>
            <select class="form-control" name="alasan_non_aktif" required>
              <option value="" disabled selected>Pilih...</option>
              <option value="Resign">Resign</option>
              <option value="Habis Kontrak">Habis Kontrak</option>
              <option value="PHK">Pemutusan Hubungan Kerja (PHK)</option>
              <option value="Pensiun">Pensiun</option>
              <option value="Meninggal Dunia">Meninggal Dunia</option>
              <option value="Lainnya">Lainnya</option>
            </select>
            <span class="field-error">Alasan non aktif wajib dipilih.</span>
          </label>

          <label class="field">
            <span class="field-label">Tanggal Berakhir <span class="req">*</span></span>
            <input class="form-control" type="date" name="start" required placeholder="dd/mm/yyyy">
            <span class="field-error">Tanggal berakhir wajib diisi.</span>
          </label>

          <label class="field">
            <span class="field-label">Keterangan</span>
            <textarea class="form-control" name="note" rows="1" placeholder="Tuliskan keterangan..." style="min-height: 38px; height: 38px;"></textarea>
          </label>
        </div>
      </section>

      <!-- Section 2: Direktorat / Divisi / Departemen -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            Direktorat / Divisi / Departemen
          </h3>
        </div>
        <div id="rows-dept">
          <div class="exc-row-item">
            <div class="exc-row-top">
              <label class="field">
                <span class="field-label">Kepada (PIC Handover)</span>
                <select class="form-control" name="pic_dept[]">
                  <option value="" selected>Pilih...</option>
                  <option value="Muhammad Rizky - 01031502441">Muhammad Rizky - 01031502441</option>
                  <option value="Dewi Kaniawati - 01031603882">Dewi Kaniawati - 01031603882</option>
                  <option value="Bambang Supriyanto - 01031401290">Bambang Supriyanto - 01031401290</option>
                  <option value="Hendro Wicaksono - 01031704512">Hendro Wicaksono - 01031704512</option>
                  <option value="Rina Oktaviani - 01031805934">Rina Oktaviani - 01031805934</option>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Obyek</span>
                <select class="form-control" name="obyek_dept[]">
                  <option value="" selected>Pilih...</option>
                  <option value="Dokumen Kerja &amp; Arsip Project">Dokumen Kerja &amp; Arsip Project</option>
                  <option value="Aset / Inventaris Departemen">Aset / Inventaris Departemen</option>
                  <option value="Akses Folder Tim &amp; Cloud Drive">Akses Folder Tim &amp; Cloud Drive</option>
                  <option value="Serah Terima Tugas &amp; Tanggung Jawab">Serah Terima Tugas &amp; Tanggung Jawab</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </label>
            </div>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="ket_dept[]" rows="2" placeholder="Tuliskan keterangan serah terima..."></textarea>
            </label>
          </div>
        </div>
        <div class="exc-actions-bar">
          <button type="button" class="btn-add-row" data-add-row="dept">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Row
          </button>
          <button type="button" class="btn-del-row" data-del-row="dept">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/></svg> Delete Row
          </button>
        </div>
      </section>

      <!-- Section 3: General Affairs -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/><path d="m14 9 3 3-3 3"/></svg>
            </span>
            General Affairs
          </h3>
        </div>
        <div id="rows-ga">
          <div class="exc-row-item">
            <div class="exc-row-top">
              <label class="field">
                <span class="field-label">Kepada</span>
                <select class="form-control" name="pic_ga[]">
                  <option value="Andi Setiawan - 010318111758" selected>Andi Setiawan - 010318111758</option>
                  <option value="Budi Santoso - 01031709841">Budi Santoso - 01031709841</option>
                  <option value="Doni Pratama - 01031912440">Doni Pratama - 01031912440</option>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Obyek</span>
                <select class="form-control" name="obyek_ga[]">
                  <option value="" selected>Pilih...</option>
                  <option value="ID Card / Kartu Akses Gedung">ID Card / Kartu Akses Gedung</option>
                  <option value="Kunci Loker / Ruangan Kerja">Kunci Loker / Ruangan Kerja</option>
                  <option value="Kendaraan Dinas / Operasional">Kendaraan Dinas / Operasional</option>
                  <option value="Kartu Parkir Perusahaan">Kartu Parkir Perusahaan</option>
                  <option value="Inventaris GA Lainnya">Inventaris GA Lainnya</option>
                </select>
              </label>
            </div>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="ket_ga[]" rows="2" placeholder="Tuliskan keterangan..."></textarea>
            </label>
          </div>
        </div>
        <div class="exc-actions-bar">
          <button type="button" class="btn-add-row" data-add-row="ga">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Row
          </button>
          <button type="button" class="btn-del-row" data-del-row="ga">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/></svg> Delete Row
          </button>
        </div>
      </section>

      <!-- Section 4: IT -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
            </span>
            IT
          </h3>
        </div>
        <div id="rows-it">
          <div class="exc-row-item">
            <div class="exc-row-top">
              <label class="field">
                <span class="field-label">Kepada</span>
                <select class="form-control" name="pic_it[]">
                  <option value="Arip Purnama - 01031605713" selected>Arip Purnama - 01031605713</option>
                  <option value="Reza Rahadian - 01031708821">Reza Rahadian - 01031708821</option>
                  <option value="Fajar Nugraha - 01031911029">Fajar Nugraha - 01031911029</option>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Obyek</span>
                <select class="form-control" name="obyek_it[]">
                  <option value="" selected>Pilih...</option>
                  <option value="Laptop / PC Unit &amp; Charger">Laptop / PC Unit &amp; Charger</option>
                  <option value="Monitor Eksternal &amp; Peripheral">Monitor Eksternal &amp; Peripheral</option>
                  <option value="Akun Email Perusahaan (GSuite / M365)">Akun Email Perusahaan (GSuite / M365)</option>
                  <option value="Akun VPN &amp; Akses Server Internal">Akun VPN &amp; Akses Server Internal</option>
                  <option value="Lisensi Software &amp; Perangkat Lunak">Lisensi Software &amp; Perangkat Lunak</option>
                  <option value="Aset IT Lainnya">Aset IT Lainnya</option>
                </select>
              </label>
            </div>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="ket_it[]" rows="2" placeholder="Tuliskan keterangan..."></textarea>
            </label>
          </div>
        </div>
        <div class="exc-actions-bar">
          <button type="button" class="btn-add-row" data-add-row="it">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Row
          </button>
          <button type="button" class="btn-del-row" data-del-row="it">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/></svg> Delete Row
          </button>
        </div>
      </section>

      <!-- Section 5: People & Culture -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            People &amp; Culture
          </h3>
        </div>
        <div id="rows-pc">
          <div class="exc-row-item">
            <div class="exc-row-top">
              <label class="field">
                <span class="field-label">Kepada</span>
                <select class="form-control" name="pic_pc[]">
                  <option value="Sofyan Farid Ma'arif - 01031808735" selected>Sofyan Farid Ma'arif - 01031808735</option>
                  <option value="Dewi Kaniawati - 01031603882">Dewi Kaniawati - 01031603882</option>
                  <option value="Nadia Putri - 01032014552">Nadia Putri - 01032014552</option>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Obyek</span>
                <select class="form-control" name="obyek_pc[]">
                  <option value="" selected>Pilih...</option>
                  <option value="Form Exit Interview">Form Exit Interview</option>
                  <option value="Kartu Asuransi Kesehatan / BPJS">Kartu Asuransi Kesehatan / BPJS</option>
                  <option value="Surat Pengalaman Kerja (Parklaring)">Surat Pengalaman Kerja (Parklaring)</option>
                  <option value="Penyelesaian Hak &amp; Kompensasi Karyawan">Penyelesaian Hak &amp; Kompensasi Karyawan</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </label>
            </div>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="ket_pc[]" rows="2" placeholder="Tuliskan keterangan..."></textarea>
            </label>
          </div>
        </div>
        <div class="exc-actions-bar">
          <button type="button" class="btn-add-row" data-add-row="pc">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Row
          </button>
          <button type="button" class="btn-del-row" data-del-row="pc">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/></svg> Delete Row
          </button>
        </div>
      </section>

      <!-- Section 6: Koperasi Karyawan -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
            </span>
            Koperasi Karyawan
          </h3>
        </div>
        <div id="rows-kop">
          <div class="exc-row-item">
            <div class="exc-row-top">
              <label class="field">
                <span class="field-label">Kepada</span>
                <select class="form-control" name="pic_kop[]">
                  <option value="Andi Setiawan - 010318111758" selected>Andi Setiawan - 010318111758</option>
                  <option value="Siti Aminah - 01031604112">Siti Aminah - 01031604112</option>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Obyek</span>
                <select class="form-control" name="obyek_kop[]">
                  <option value="" selected>Pilih...</option>
                  <option value="Klirens Simpanan Pokok &amp; Wajib">Klirens Simpanan Pokok &amp; Wajib</option>
                  <option value="Klirens Sisa Pinjaman Anggota">Klirens Sisa Pinjaman Anggota</option>
                  <option value="Formulir Pengunduran Diri Anggota Koperasi">Formulir Pengunduran Diri Anggota Koperasi</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </label>
            </div>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="ket_kop[]" rows="2" placeholder="Tuliskan keterangan..."></textarea>
            </label>
          </div>
        </div>
        <div class="exc-actions-bar">
          <button type="button" class="btn-add-row" data-add-row="kop">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Row
          </button>
          <button type="button" class="btn-del-row" data-del-row="kop">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/></svg> Delete Row
          </button>
        </div>
      </section>

      <!-- Section 7: Application Management -->
      <section class="exc-card">
        <div class="exc-card-head">
          <h3 class="exc-card-title">
            <span class="exc-card-title-icon">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/></svg>
            </span>
            Application Management
          </h3>
        </div>
        <div id="rows-app">
          <div class="exc-row-item">
            <div class="exc-row-top">
              <label class="field">
                <span class="field-label">Kepada</span>
                <select class="form-control" name="pic_app[]">
                  <option value="Iwan Sholahudin - 01031710668" selected>Iwan Sholahudin - 01031710668</option>
                  <option value="Ahmad Dahlan - 01031809221">Ahmad Dahlan - 01031809221</option>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Obyek</span>
                <select class="form-control" name="obyek_app[]">
                  <option value="" selected>Pilih...</option>
                  <option value="Penonaktifan Akun ERP / SAP System">Penonaktifan Akun ERP / SAP System</option>
                  <option value="Penonaktifan Akun HRIS MATTA">Penonaktifan Akun HRIS MATTA</option>
                  <option value="Pencabutan Hak Akses Database &amp; Server Cloud">Pencabutan Hak Akses Database &amp; Server Cloud</option>
                  <option value="Pencabutan Akses Repository GitHub / GitLab">Pencabutan Akses Repository GitHub / GitLab</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </label>
            </div>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="ket_app[]" rows="2" placeholder="Tuliskan keterangan..."></textarea>
            </label>
          </div>
        </div>
        <div class="exc-actions-bar">
          <button type="button" class="btn-add-row" data-add-row="app">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Row
          </button>
          <button type="button" class="btn-del-row" data-del-row="app">
            <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/></svg> Delete Row
          </button>
        </div>
      </section>
    </div>

    <div class="modal-footer" style="display: flex; flex-direction: column; align-items: stretch; gap: 14px; padding: 18px 28px 16px; background: #ffffff; border-top: 1.5px solid #e2e8f0;">
      <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <button class="btn btn-exc-back" type="button" id="btn-back-to-statement">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg> Back
        </button>
        <button class="btn btn-exc-submit" type="submit">
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

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var btnAgree = document.getElementById("btn-agree-statement");
    if (btnAgree) {
      btnAgree.addEventListener("click", function () {
        if (window.MATTA && window.MATTA.closeModal && window.MATTA.openModal) {
          window.MATTA.closeModal("modal-statement");
          setTimeout(function () {
            window.MATTA.openModal("modal-module");
          }, 150);
        } else {
          var mStmt = document.getElementById("modal-statement");
          var mMod = document.getElementById("modal-module");
          if (mStmt) {
            mStmt.classList.remove("open");
            mStmt.setAttribute("aria-hidden", "true");
          }
          if (mMod) {
            mMod.classList.add("open");
            mMod.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
          }
        }
      });
    }

    var btnBack = document.getElementById("btn-back-to-statement");
    if (btnBack) {
      btnBack.addEventListener("click", function () {
        if (window.MATTA && window.MATTA.closeModal && window.MATTA.openModal) {
          window.MATTA.closeModal("modal-module");
          setTimeout(function () {
            window.MATTA.openModal("modal-statement");
          }, 150);
        } else {
          var mMod = document.getElementById("modal-module");
          var mStmt = document.getElementById("modal-statement");
          if (mMod) {
            mMod.classList.remove("open");
            mMod.setAttribute("aria-hidden", "true");
          }
          if (mStmt) {
            mStmt.classList.add("open");
            mStmt.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
          }
        }
      });
    }

    // Dynamic row addition & deletion for all 7 sections
    document.querySelectorAll("[data-add-row]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var key = this.getAttribute("data-add-row");
        var container = document.getElementById("rows-" + key);
        if (!container) return;
        var first = container.querySelector(".exc-row-item");
        if (!first) return;
        var clone = first.cloneNode(true);
        clone.querySelectorAll("input, textarea").forEach(function (el) {
          el.value = "";
        });
        clone.querySelectorAll("select").forEach(function (el) {
          el.selectedIndex = 0;
        });
        container.appendChild(clone);
      });
    });

    document.querySelectorAll("[data-del-row]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var key = this.getAttribute("data-del-row");
        var container = document.getElementById("rows-" + key);
        if (!container) return;
        var rows = container.querySelectorAll(".exc-row-item");
        if (rows.length > 1) {
          rows[rows.length - 1].remove();
        } else if (window.MATTA && window.MATTA.toast) {
          window.MATTA.toast("Minimal harus ada 1 baris untuk bagian ini.", "error");
        }
      });
    });

    // Handle auto-open via query param (?open=statement or ?apply=true)
    var params = new URLSearchParams(window.location.search);
    if (params.get("open") === "statement" || params.get("apply") === "true") {
      setTimeout(function () {
        if (window.MATTA && window.MATTA.openModal) {
          window.MATTA.openModal("modal-statement");
        }
      }, 250);
    }
  });
</script>
@endpush
