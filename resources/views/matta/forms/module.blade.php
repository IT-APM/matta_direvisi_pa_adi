@extends('matta.layouts.app', ['activeNav' => 'form-' . $module])

@section('title', $mod['name'])
@section('body_module', $module)

@section('breadcrumb')
<nav class="breadcrumb" aria-label="Breadcrumb">
  <a href="{{ route('matta.dashboard') }}">Home</a>
  <span class="sep">/</span>
  <span>Forms</span>
  <span class="sep">/</span>
  <span class="current">{{ $mod['name'] }}</span>
</nav>
@endsection

@section('content')
<main class="page-content">
  <div class="page-head">
    <div class="page-head-left">
      <button class="back-btn" type="button" data-back href="{{ route('matta.dashboard') }}" aria-label="Kembali">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
      </button>
      <div class="mod-title-head">
        <div class="dt-icon" style="width:44px;height:44px">
          <img src="{{ asset('matta-ui/img/modules/' . $module . '.png') }}" alt="" style="width:28px;height:28px">
        </div>
        <div>
          <h1 class="page-title">{{ $mod['name'] }}</h1>
          <p class="page-sub">{{ $mod['desc'] }}</p>
        </div>
      </div>
    </div>

    @if($module === 'exit-clearance')
      <button class="btn btn-primary" type="button" data-modal-open="modal-statement">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        <span>{{ $mod['apply'] }}</span>
      </button>
    @else
      <button class="btn btn-primary" type="button" data-modal-open="modal-module">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        <span>{{ $mod['apply'] }}</span>
      </button>
    @endif
  </div>

  <!-- Tabel Riwayat Pengajuan -->
  <section class="dt-card" data-datatable data-file="form-{{ $module }}" data-label="pengajuan {{ strtolower($mod['short']) }}">
    <div class="dt-head">
      <div class="dt-head-left">
        <div class="dt-icon">
          <img src="{{ asset('matta-ui/img/modules/' . $module . '.png') }}" alt="" style="width:24px;height:24px">
        </div>
        <div>
          <h2 class="dt-title">{{ $mod['list'] }}</h2>
          <p class="dt-sub">Klik baris atau ikon mata untuk melihat rincian formulir dan progres persetujuan</p>
        </div>
      </div>
      <div class="dt-head-right">
        <div class="dt-exports" role="group" aria-label="Ekspor tabel">
          <button class="btn btn-outline btn-xs" type="button" data-dt-export="copy">Copy</button>
          <button class="btn btn-outline btn-xs" type="button" data-dt-export="csv">CSV</button>
          <button class="btn btn-outline btn-xs" type="button" data-dt-export="excel">Excel</button>
          <button class="btn btn-outline btn-xs" type="button" data-dt-export="pdf">PDF</button>
          <button class="btn btn-outline btn-xs" type="button" data-dt-export="print">Print</button>
        </div>
      </div>
    </div>

    <div class="dt-toolbar">
      <label class="dt-length">
        <span>Show</span>
        <select class="form-control form-control-sm" data-dt-length>
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span>entries</span>
      </label>

      <div class="dt-toolbar-right">
        <label class="dt-search">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input class="form-control form-control-sm" type="search" data-dt-search placeholder="Cari ID, nama, status...">
        </label>
      </div>
    </div>

    <div class="table-wrap">
      <table class="dt">
        <thead>
          <tr>
            <th style="width:48px" data-sort="num">No</th>
            <th data-sort="text">ID Formulir</th>
            <th data-sort="text">Pengaju</th>
            <th data-sort="text">Jabatan</th>
            <th data-sort="text">Unit Kerja</th>
            <th data-sort="date">Tanggal &amp; Jam</th>
            <th class="center">Status</th>
            <th class="center" style="width:68px" data-noexport>Detail</th>
          </tr>
        </thead>
        <tbody>
          @forelse($mod['rows'] as $idx => $r)
            @php
              $stColor = $r['status'] === 'Approved' ? 'green' : ($r['status'] === 'Rejected' ? 'red' : 'orange');
              $detailObj = [
                'eyebrow' => $mod['name'],
                'title' => $r['id'],
                'module' => $module,
                'img' => asset('matta-ui/img/modules/' . $module . '.png'),
                'badges' => [[$r['status'], $stColor]],
                'person' => ['name' => $r['name'], 'nip' => $r['nip'], 'role' => $r['role']],
                'sections' => [
                  [
                    'h' => 'Detail Pengajuan',
                    'rows' => [
                      ['ID Formulir', $r['id']],
                      ['Jenis Formulir', $r['form']],
                      ['Tanggal', $r['date']],
                      ['Jam', $r['time']],
                      ['Keterangan', $r['note'] ?? '-'],
                      ['Unit Kerja', $r['full_unit'] ?? $u['unit']],
                    ]
                  ]
                ],
                'attachment' => ($r['file'] && $r['file'] !== '-') ? $r['file'] : null,
                'timeline' => [
                  ['t' => 'Diajukan', 'by' => $r['name'], 'at' => $r['date'] . ' ' . $r['time'], 'state' => 'done'],
                  ['t' => 'Persetujuan Atasan', 'by' => $u['approver'], 'at' => $r['status'] === 'Approved' ? $r['date'] : '', 'state' => $r['status'] === 'Approved' ? 'done' : 'current'],
                  ['t' => 'Verifikasi Human Capital', 'by' => 'Human Capital', 'at' => '', 'state' => $r['status'] === 'Approved' ? 'done' : 'todo'],
                ]
              ];
            @endphp
            <tr data-detail='@json($detailObj)'>
              <td class="num" data-dt-no>{{ $idx + 1 }}</td>
              <td><span class="id-chip">{{ $r['id'] }}</span></td>
              <td>
                <div class="person">
                  <span class="avatar-initial">{{ $u['initials'] }}</span>
                  <div>
                    <div class="person-name">{{ $r['name'] }}</div>
                    <div class="person-sub">{{ $r['nip'] }}</div>
                  </div>
                </div>
              </td>
              <td>{{ $r['role'] }}</td>
              <td class="cell-2">
                <div>{{ $u['company'] }}</div>
                <div class="sub-line">{{ $u['division'] }} / {{ $u['department'] }}</div>
              </td>
              <td class="cell-2" data-value="{{ $r['iso_date'] ?? $r['date'] }}">
                <div class="main-line">{{ $r['date'] }}</div>
                <div class="sub-line">{{ $r['time'] }}</div>
              </td>
              <td class="center">
                <span class="badge badge-pill badge-{{ $stColor }}">{{ $r['status'] }}</span>
              </td>
              <td class="center">
                <button class="icon-btn" type="button" data-row-detail data-title="Detail {{ $r['id'] }}" aria-label="Lihat detail">
                  <svg class="icon" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </td>
            </tr>
          @empty
            <tr class="dt-empty">
              <td colspan="8" style="text-align:center;padding:32px 16px;color:#888;">Belum ada pengajuan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="dt-foot">
      <div class="dt-info" data-dt-info></div>
      <div class="dt-pager" data-dt-pager></div>
    </div>
  </section>
</main>
@endsection

@section('modals')
@if($module === 'exit-clearance')
  <style>
    .exc-modal { max-width: 820px; width: 95vw; }
    .exc-body { display: flex; flex-direction: column; gap: 16px; max-height: 70vh; overflow-y: auto; background: #f8fafc; padding: 20px 24px; }
    .exc-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); }
    .exc-card-head { margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9; }
    .exc-card-title { margin: 0; font-size: 14px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; }
    .exc-card-title-icon { width: 28px; height: 28px; border-radius: 7px; background: #eff6ff; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; }
    .exc-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .exc-row-item { padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 10px; }
    .exc-row-top { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px; }
    .exc-actions-bar { display: flex; gap: 8px; margin-top: 8px; }
    .btn-add-row, .btn-del-row { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 8px; cursor: pointer; border: 1px solid transparent; }
    .btn-add-row { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .btn-del-row { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    @media (max-width: 768px) {
      .exc-grid-3, .exc-row-top { grid-template-columns: 1fr; }
    }
  </style>

  <!-- Modal 1: Surat Pernyataan Exit Clearance -->
  <div class="modal-backdrop" id="modal-statement" aria-hidden="true">
    <div class="modal modal-lg" role="dialog" aria-modal="true" aria-labelledby="stmt-title" style="max-width:680px">
      <div class="modal-header">
        <div class="dt-icon" style="width:40px;height:40px">
          <img src="{{ asset('matta-ui/img/modules/exit-clearance.png') }}" alt="" style="width:28px;height:28px">
        </div>
        <div>
          <div class="modal-title" id="stmt-title">Surat Pernyataan Karyawan</div>
          <div class="modal-sub">Persetujuan Ketentuan Exit Clearance &amp; Pengembalian Aset Perusahaan</div>
        </div>
        <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <div class="modal-body" style="font-size:13.5px;line-height:1.65;color:#334155">
        <p style="margin-top:0">Saya yang bertanda tangan di bawah ini:</p>
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px 18px;margin-bottom:16px;display:grid;grid-template-columns:140px 1fr;row-gap:6px;font-size:13px">
          <span class="muted">Nama Lengkap</span><b data-stmt-name>{{ $u['name'] }}</b>
          <span class="muted">NIP</span><b class="mono" data-stmt-nip>{{ $u['nip'] }}</b>
          <span class="muted">Jabatan</span><b data-stmt-position>{{ $u['role'] }}</b>
          <span class="muted">Direktorat</span><b data-stmt-directorate>{{ $u['directorate'] }}</b>
          <span class="muted">Divisi</span><b data-stmt-division>{{ $u['division'] }}</b>
          <span class="muted">Departemen</span><b data-stmt-department>{{ $u['department'] }}</b>
        </div>
        <p>Dengan ini menyatakan bersedia menyelesaikan seluruh kewajiban serah terima pekerjaan, dokumen, inventaris perangkat kerja, serta administrasi lintas departemen (Direktorat, General Affairs, IT, People &amp; Culture, Koperasi, dan Application Management) sesuai prosedur yang berlaku di <b>{{ $u['company'] }}</b>.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" type="button" data-modal-close>Batal</button>
        <button class="btn btn-primary" type="button" id="btn-agree-statement">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          Saya Setuju &amp; Lanjutkan
        </button>
      </div>
    </div>
  </div>

  <!-- Modal 2: Form Exit Clearance 7 Bagian -->
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

      <input type="file" name="file" class="hidden" style="display:none">

      <div class="modal-body exc-body">
        <section class="exc-card">
          <div class="exc-card-head">
            <h3 class="exc-card-title">
              <span class="exc-card-title-icon"><svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
              1. Karyawan
            </h3>
          </div>
          <div class="exc-grid-3">
            <label class="field">
              <span class="field-label">Alasan Non Aktif <span class="req">*</span></span>
              <select class="form-control" name="alasan_non_aktif" required>
                <option value="" disabled selected>Pilih...</option>
                <option value="Resign">Resign</option>
                <option value="Habis Kontrak">Habis Kontrak</option>
                <option value="Pensiun">Pensiun</option>
                <option value="Lainnya">Lainnya</option>
              </select>
              <span class="field-error">Alasan non aktif wajib dipilih.</span>
            </label>
            <label class="field">
              <span class="field-label">Tanggal Berakhir <span class="req">*</span></span>
              <input class="form-control" type="date" name="start" required value="{{ date('Y-m-d') }}">
              <span class="field-error">Tanggal berakhir wajib diisi.</span>
            </label>
            <label class="field">
              <span class="field-label">Keterangan</span>
              <textarea class="form-control" name="note" rows="1" placeholder="Tuliskan keterangan..."></textarea>
            </label>
          </div>
        </section>

        @php
          $excSections = [
            ['key' => 'dept', 'title' => '2. Direktorat / Divisi / Departemen', 'pics' => [$u['approver'] . ' - ' . $u['approver_nip'], 'Dewi Kaniawati - 01031603882'], 'objects' => ['Dokumen Kerja & Arsip Project', 'Aset / Inventaris Departemen', 'Serah Terima Tugas & Tanggung Jawab']],
            ['key' => 'ga', 'title' => '3. General Affairs', 'pics' => ['Andi Setiawan - 010318111758', 'Budi Santoso - 01031709841'], 'objects' => ['ID Card / Kartu Akses Gedung', 'Kunci Loker / Ruangan Kerja', 'Kendaraan Dinas / Operasional']],
            ['key' => 'it', 'title' => '4. IT Infrastructure', 'pics' => ['Arip Purnama - 01031605713', 'Fajar Nugraha - 01031911029'], 'objects' => ['Laptop / PC Unit & Charger', 'Akun Email Perusahaan (GSuite / M365)', 'Akun VPN & Akses Server Internal']],
            ['key' => 'pc', 'title' => '5. People & Culture', 'pics' => ["Sofyan Farid Ma'arif - 01031808735", 'Dewi Kaniawati - 01031603882'], 'objects' => ['Form Exit Interview', 'Kartu Asuransi Kesehatan / BPJS', 'Surat Pengalaman Kerja (Parklaring)']],
            ['key' => 'kop', 'title' => '6. Koperasi Karyawan', 'pics' => ['Andi Setiawan - 010318111758', 'Siti Aminah - 01031604112'], 'objects' => ['Klirens Simpanan Pokok & Wajib', 'Klirens Sisa Pinjaman Anggota']],
            ['key' => 'app', 'title' => '7. Application Management', 'pics' => ['Iwan Sholahudin - 01031710668', 'Ahmad Dahlan - 01031809221'], 'objects' => ['Penonaktifan Akun ERP / SAP System', 'Penonaktifan Akun HRIS MATTA', 'Pencabutan Akses Repository GitHub / GitLab']],
          ];
        @endphp

        @foreach($excSections as $sec)
          <section class="exc-card">
            <div class="exc-card-head">
              <h3 class="exc-card-title">
                <span class="exc-card-title-icon"><svg class="icon icon-sm" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/></svg></span>
                {{ $sec['title'] }}
              </h3>
            </div>
            <div id="rows-{{ $sec['key'] }}">
              <div class="exc-row-item">
                <div class="exc-row-top">
                  <label class="field">
                    <span class="field-label">Kepada (PIC)</span>
                    <select class="form-control" name="pic_{{ $sec['key'] }}[]">
                      @foreach($sec['pics'] as $pic)
                        <option value="{{ $pic }}">{{ $pic }}</option>
                      @endforeach
                    </select>
                  </label>
                  <label class="field">
                    <span class="field-label">Obyek Serah Terima</span>
                    <select class="form-control" name="obyek_{{ $sec['key'] }}[]">
                      <option value="" selected>Pilih...</option>
                      @foreach($sec['objects'] as $obj)
                        <option value="{{ $obj }}">{{ $obj }}</option>
                      @endforeach
                    </select>
                  </label>
                </div>
                <label class="field">
                  <span class="field-label">Keterangan</span>
                  <textarea class="form-control" name="ket_{{ $sec['key'] }}[]" rows="2" placeholder="Tuliskan keterangan serah terima..."></textarea>
                </label>
              </div>
            </div>
            <div class="exc-actions-bar">
              <button type="button" class="btn-add-row" data-add-row="{{ $sec['key'] }}">+ Add Row</button>
              <button type="button" class="btn-del-row" data-del-row="{{ $sec['key'] }}">− Delete Row</button>
            </div>
          </section>
        @endforeach
      </div>

      <div class="modal-footer" style="justify-content:space-between">
        <button class="btn btn-outline" type="button" id="btn-back-to-statement">← Kembali ke Pernyataan</button>
        <button class="btn btn-primary" type="submit">Submit Exit Clearance</button>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var btnAgree = document.getElementById("btn-agree-statement");
      if (btnAgree) {
        btnAgree.addEventListener("click", function () {
          if (window.MATTA && window.MATTA.closeModal && window.MATTA.openModal) {
            window.MATTA.closeModal("modal-statement");
            setTimeout(function () { window.MATTA.openModal("modal-module"); }, 150);
          }
        });
      }
      var btnBack = document.getElementById("btn-back-to-statement");
      if (btnBack) {
        btnBack.addEventListener("click", function () {
          if (window.MATTA && window.MATTA.closeModal && window.MATTA.openModal) {
            window.MATTA.closeModal("modal-module");
            setTimeout(function () { window.MATTA.openModal("modal-statement"); }, 150);
          }
        });
      }
      document.querySelectorAll("[data-add-row]").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var key = this.getAttribute("data-add-row");
          var container = document.getElementById("rows-" + key);
          if (!container) return;
          var first = container.querySelector(".exc-row-item");
          if (!first) return;
          var clone = first.cloneNode(true);
          clone.querySelectorAll("input, textarea").forEach(function (el) { el.value = ""; });
          container.appendChild(clone);
        });
      });
      document.querySelectorAll("[data-del-row]").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var key = this.getAttribute("data-del-row");
          var container = document.getElementById("rows-" + key);
          if (!container) return;
          var rows = container.querySelectorAll(".exc-row-item");
          if (rows.length > 1) rows[rows.length - 1].remove();
          else if (window.MATTA && window.MATTA.toast) window.MATTA.toast("Minimal harus ada 1 baris untuk bagian ini.", "error");
        });
      });
      var params = new URLSearchParams(window.location.search);
      if (params.get("open") === "statement" || params.get("apply") === "true") {
        setTimeout(function () {
          if (window.MATTA && window.MATTA.openModal) window.MATTA.openModal("modal-statement");
        }, 250);
      }
    });
  </script>
  @endpush
@else
  <!-- Modal Standar / Spesifik untuk 7 Modul Lainnya -->
  <div class="modal-backdrop" id="modal-module" aria-hidden="true">
    <form class="modal" id="form-module" data-title="{{ $mod['name'] }}" data-prefix="{{ $mod['prefix'] }}" data-name="{{ strtolower($mod['short']) }}" role="dialog" aria-modal="true" aria-labelledby="mod-title" novalidate>
      <div class="modal-header">
        <div class="dt-icon" style="width:40px;height:40px">
          <img src="{{ asset('matta-ui/img/modules/' . $module . '.png') }}" alt="" style="width:26px;height:26px">
        </div>
        <div>
          <div class="modal-title" id="mod-title">{{ $mod['apply'] }}</div>
          <div class="modal-sub">{{ $mod['desc'] }}</div>
        </div>
        <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-grid">
          <label class="field">
            <span class="field-label">Tanggal Mulai <span class="req">*</span></span>
            <input class="form-control" type="date" name="start" required value="{{ date('Y-m-d') }}">
            <span class="field-error">Tanggal mulai wajib diisi.</span>
          </label>
          <label class="field">
            <span class="field-label">Tanggal Selesai <span class="req">*</span></span>
            <input class="form-control" type="date" name="end" required value="{{ date('Y-m-d') }}">
            <span class="field-error">Tanggal selesai wajib diisi.</span>
          </label>

          @if(in_array($module, ['leave', 'sick-leave']))
            <label class="field">
              <span class="field-label">PIC Pengganti Selama Cuti / Sakit <span class="req">*</span></span>
              <input class="form-control" type="text" name="pic" required value="{{ $u['approver'] }}" placeholder="Nama rekan pengganti...">
              <span class="field-error">PIC pengganti wajib diisi.</span>
            </label>
            <label class="field">
              <span class="field-label">Nomor Telepon yang Dapat Dihubungi <span class="req">*</span></span>
              <input class="form-control mono" type="text" name="phone" required value="{{ optional($u['karyawan'])->telepon1 ?: '081234567890' }}">
              <span class="field-error">Nomor telepon wajib diisi.</span>
            </label>
            <label class="field span-2">
              <span class="field-label">Alamat Selama Cuti / Istirahat Sakit</span>
              <input class="form-control" type="text" name="address" value="{{ optional($u['karyawan'])->alamat_domisili ?: 'Jakarta' }}">
            </label>
          @endif

          <label class="field span-2">
            <span class="field-label">Alasan / Keterangan Pengajuan <span class="req">*</span></span>
            <textarea class="form-control" name="note" rows="3" required placeholder="Tuliskan alasan atau rincian pengajuan secara jelas..."></textarea>
            <span class="field-error">Keterangan wajib diisi.</span>
          </label>

          <label class="field span-2">
            <span class="field-label">Lampiran Dokumen Pendukung (PDF / Gambar)</span>
            <input class="form-control" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" type="button" data-modal-close>Batal</button>
        <button class="btn btn-primary" type="submit">Kirim Pengajuan</button>
      </div>
    </form>
  </div>
@endif
@endsection

