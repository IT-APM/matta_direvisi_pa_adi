@extends('matta.layouts.app', ['activeNav' => 'health'])

@section('title', 'Health')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="Breadcrumb">
  <a href="{{ route('matta.dashboard') }}">Home</a>
  <span class="sep">/</span>
  <span class="current">Health</span>
</nav>
@endsection

@section('content')
<main class="page-content">
  <div class="page-head">
    <div class="page-head-left">
      <button class="back-btn" type="button" data-back href="{{ route('matta.dashboard') }}" aria-label="Kembali">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
      </button>
      <div>
        <h1 class="page-title">Health Declaration</h1>
        <p class="page-sub">Pemantauan kondisi kesehatan harian karyawan dan lingkungan sekitar (Ring 1, Ring 2, dan Ring 3).</p>
      </div>
    </div>
    <span class="badge badge-pill badge-dot badge-green" data-health-result hidden>Terkirim — kondisi aman</span>
  </div>

  <form class="card" id="form-health" novalidate style="padding:24px">
    <div class="accordion">
      <!-- RING 1 -->
      <div class="acc-item">
        <button class="acc-trigger" type="button" data-acc-trigger aria-expanded="true" aria-controls="acc-ring1">
          <span class="acc-num">1</span>
          <span class="acc-meta">
            <span class="acc-title">Ring 1 — Diri Sendiri &amp; Keluarga Serumah</span>
            <span class="acc-desc">Kondisi kesehatan Anda dan anggota keluarga yang tinggal satu atap hari ini.</span>
          </span>
          <svg class="icon acc-caret" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="acc-panel open" id="acc-ring1">
          <div class="form-grid">
            <label class="field">
              <span class="field-label">Kondisi Kesehatan Anda Hari Ini <span class="req">*</span></span>
              <select class="form-control" name="r1_condition" required>
                <option value="" disabled selected>Pilih kondisi...</option>
                <option value="sehat">Sehat — Siap Bekerja</option>
                <option value="kurang-fit">Kurang Fit (Gejala Ringan)</option>
                <option value="tidak-sehat">Tidak Sehat / Sedang Sakit</option>
              </select>
              <span class="field-error">Wajib dipilih.</span>
            </label>
            <label class="field">
              <span class="field-label">Kondisi Keluarga Serumah <span class="req">*</span></span>
              <select class="form-control" name="r1_family" required>
                <option value="" disabled selected>Pilih kondisi...</option>
                <option value="tidak">Seluruhnya Sehat</option>
                <option value="ya">Ada yang Sedang Sakit / Demam</option>
              </select>
              <span class="field-error">Wajib dipilih.</span>
            </label>
            <label class="field span-2">
              <span class="field-label">Catatan Gejala / Keterangan Tambahan (Opsional)</span>
              <textarea class="form-control" name="r1_note" rows="2" placeholder="Kosongkan apabila seluruhnya dalam kondisi sehat..."></textarea>
            </label>
          </div>
        </div>
      </div>

      <!-- RING 2 -->
      <div class="acc-item">
        <button class="acc-trigger" type="button" data-acc-trigger aria-expanded="true" aria-controls="acc-ring2">
          <span class="acc-num">2</span>
          <span class="acc-meta">
            <span class="acc-title">Ring 2 — Lingkungan Tempat Tinggal (RT / RW / Kompleks)</span>
            <span class="acc-desc">Kondisi kesehatan tetangga dekat atau lingkungan sekitar tempat tinggal Anda.</span>
          </span>
          <svg class="icon acc-caret" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="acc-panel open" id="acc-ring2">
          <div class="form-grid">
            <label class="field">
              <span class="field-label">Apakah Ada Kasus Penyakit Menular di Lingkungan Sekitar? <span class="req">*</span></span>
              <select class="form-control" name="r2_env" required>
                <option value="" disabled selected>Pilih jawaban...</option>
                <option value="tidak">Tidak Ada — Lingkungan Aman</option>
                <option value="ya">Ya — Ada Kasus di Sekitar Rumah</option>
              </select>
              <span class="field-error">Wajib dipilih.</span>
            </label>
            <label class="field">
              <span class="field-label">Kontak Erat dalam 7 Hari Terakhir <span class="req">*</span></span>
              <select class="form-control" name="r2_contact" required>
                <option value="" disabled selected>Pilih jawaban...</option>
                <option value="tidak">Tidak Pernah Kontak Erat</option>
                <option value="ya">Pernah Kontak Erat</option>
              </select>
              <span class="field-error">Wajib dipilih.</span>
            </label>
          </div>
        </div>
      </div>

      <!-- RING 3 -->
      <div class="acc-item">
        <button class="acc-trigger" type="button" data-acc-trigger aria-expanded="true" aria-controls="acc-ring3">
          <span class="acc-num">3</span>
          <span class="acc-meta">
            <span class="acc-title">Ring 3 — Lokasi Kerja &amp; Mobilitas Perjalanan</span>
            <span class="acc-desc">Mode kerja hari ini dan sarana transportasi menuju kantor/lokasi tugas.</span>
          </span>
          <svg class="icon acc-caret" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="acc-panel open" id="acc-ring3">
          <div class="form-grid">
            <label class="field">
              <span class="field-label">Lokasi Kerja Hari Ini <span class="req">*</span></span>
              <select class="form-control" name="r3_location" required>
                <option value="" disabled selected>Pilih lokasi...</option>
                <option value="wfo">Work From Office (HQ / Cabang)</option>
                <option value="wfh">Work From Home (WFH)</option>
                <option value="site">Kunjungan Klien / Tugas Lapangan</option>
              </select>
              <span class="field-error">Wajib dipilih.</span>
            </label>
            <label class="field">
              <span class="field-label">Transportasi yang Digunakan <span class="req">*</span></span>
              <select class="form-control" name="r3_transport" required>
                <option value="" disabled selected>Pilih transportasi...</option>
                <option value="pribadi">Kendaraan Pribadi</option>
                <option value="umum">Transportasi Umum (KRL / MRT / TransJakarta)</option>
                <option value="ojol">Transportasi Online</option>
                <option value="tidak-berpergian">Tidak Bepergian (WFH)</option>
              </select>
              <span class="field-error">Wajib dipilih.</span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--slate-200)">
      <button class="btn btn-outline" type="reset">Reset Isian</button>
      <button class="btn btn-primary" type="submit">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
        Simpan Deklarasi Kesehatan
      </button>
    </div>
  </form>
</main>
@endsection

