@extends('matta.layouts.app', ['activeNav' => 'profile'])

@section('title', 'My Profile')

@section('content')
<main class="main" id="main">
<nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('matta.dashboard') }}">Home</a><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span class="current" aria-current="page" data-crumb-current>My Profile</span></nav>
<div class="profile-wrap">
<section class="profile-hero">
  <div class="hero-avatar">
    <svg class="icon icon-fill icon-lg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0Z"/></svg>
    <span class="online" title="Online"></span>
  </div>
  <h1 class="hero-name" data-hero-name>{{ $u['name'] }}</h1>
  <p class="hero-email" data-hero-email>{{ $u['email'] }}</p>
  <p class="hero-meta">{{ $u['role'] }} &nbsp;•&nbsp; {{ $u['company'] }}</p>
</section>

<div class="profile-cols">
  <form class="p-card" id="form-profile" data-server-populated="true" novalidate>
    <div class="p-card-head">
      <div>
        <h3>Informasi Pegawai &amp; Organisasi</h3>
        <p>Data profil personal dan penugasan struktural</p>
      </div>
      <span class="badge badge-pill badge-dot badge-green">Aktif</span>
    </div>
    <div class="p-card-body">
      <div class="p-section">
        Data Pribadi 
        <button type="button" class="icon-btn" data-profile-edit aria-pressed="true" aria-label="Ubah data pribadi">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/></svg>
        </button>
        <span class="muted-note" data-profile-mode>Mode Edit Aktif</span>
      </div>
      <div class="p-row">
        <label>Nama Lengkap</label>
        <div class="field">
          <input class="form-control" name="full_name" value="{{ $u['name'] }}" required>
          <span class="field-error">Nama wajib diisi.</span>
        </div>
      </div>
      <div class="p-row">
        <label>Tanggal Lahir</label>
        <input class="form-control" type="date" name="birth" value="{{ optional($k)->tanggal_lahir ?: '1998-05-14' }}">
      </div>
      <div class="p-row">
        <label>Jenis Kelamin</label>
        <select class="form-control" name="gender">
          @php $jk = optional($k)->_kelamin?->nama ?: (optional($k)->jenis_kelamin ?: 'Laki-laki / Male'); @endphp
          <option value="Laki-laki / Male" {{ str_contains(strtolower($jk), 'laki') ? 'selected' : '' }}>Laki-laki / Male</option>
          <option value="Perempuan / Female" {{ str_contains(strtolower($jk), 'perempuan') ? 'selected' : '' }}>Perempuan / Female</option>
        </select>
      </div>
      <div class="p-row">
        <label>Kewarganegaraan</label>
        <input class="form-control" name="nationality" value="{{ optional($k)->_warga?->nama ?: (optional($k)->kewarganegaraan ?: 'Indonesia') }}">
      </div>
      <div class="p-row">
        <label>Nomor Telepon / WA</label>
        <div class="field">
          <input class="form-control mono" type="tel" name="phone" value="{{ optional($k)->telepon1 ?: (optional($k)->telepon ?: '081234567890') }}" required>
          <span class="field-error">Nomor telepon wajib diisi.</span>
        </div>
      </div>
      <div class="p-row">
        <label>Email Pribadi / Akun</label>
        <div class="field">
          <input class="form-control" type="email" name="email" value="{{ $u['email'] }}" required>
          <span class="field-error">Format email tidak valid.</span>
        </div>
      </div>
      <div class="p-row align-top">
        <label>Alamat Domisili</label>
        <textarea class="form-control" name="address">{{ optional($k)->alamat_domisili ?: (optional($k)->alamat_ktp ?: 'Jakarta, Indonesia') }}</textarea>
      </div>
      <div class="p-section" style="margin-top:8px">
        Detail Pekerjaan &amp; Organisasi 
        <span class="badge badge-gray" style="font-weight:500;letter-spacing:0;text-transform:none">
          <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Hanya Baca / Read-Only
        </span>
      </div>
      <div>
        <div class="kv-row"><span>Nomor Induk Pegawai (NIP):</span><span class="v"><span class="mono">{{ $u['nip'] }}</span></span></div>
        <div class="kv-row"><span>Perusahaan:</span><span class="v">{{ $u['company'] }}</span></div>
        <div class="kv-row"><span>Direktorat:</span><span class="v">{{ $u['directorate'] }}</span></div>
        <div class="kv-row"><span>Divisi:</span><span class="v">{{ $u['division'] }}</span></div>
        <div class="kv-row"><span>Posisi / Level:</span><span class="v">{{ $u['role'] }} @if(!empty($u['level']))<small>({{ $u['level'] }})</small>@endif</span></div>
        <div class="kv-row"><span>Status Kepegawaian:</span><span class="v"><span class="badge badge-amber">{{ $u['status_karyawan'] }}</span></span></div>
        <div class="kv-row"><span>Jadwal Kerja:</span><span class="v">{{ $u['wg'] }}</span></div>
      </div>
    </div>
    <div class="p-card-foot" data-profile-foot>
      <span class="note">
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        Perubahan data pribadi akan diverifikasi oleh tim HR.
      </span>
      <button class="btn btn-outline" type="button" data-profile-cancel>Batal</button>
      <button class="btn btn-primary" type="submit" style="padding:0 24px">Simpan</button>
    </div>
  </form>

  <section class="p-card">
    <div class="p-card-head">
      <div>
        <h3>Legalitas, Akses &amp; Alur Persetujuan</h3>
        <p>Identitas kependudukan, kepatuhan, serta otorisasi (dikelola oleh HR/Admin)</p>
      </div>
      <span class="badge badge-pill badge-dot badge-blue">Terverifikasi</span>
    </div>
    <div class="p-card-body">
      <div class="p-section">Legalitas &amp; Pajak</div>
      <div>
        <div class="kv-row"><span>NIK KTP:</span><span class="v"><span class="mono">{{ optional($k)->no_ktp ?: '-' }}</span></span></div>
        <div class="kv-row"><span>NPWP:</span><span class="v"><span class="badge badge-green">{{ optional($k)->no_npwp ? 'Terdaftar (' . optional($k)->no_npwp . ')' : 'Terdaftar' }}</span></span></div>
        <div class="kv-row"><span>BPJS Kesehatan:</span><span class="v"><span class="badge badge-green badge-dot">{{ optional($k)->no_bpjs ? 'Terdaftar (' . optional($k)->no_bpjs . ')' : 'Terdaftar Aktif' }}</span></span></div>
        <div class="kv-row"><span>BPJS Ketenagakerjaan:</span><span class="v"><span class="badge badge-green badge-dot">{{ optional($k)->no_bpjstk ? 'Terdaftar (' . optional($k)->no_bpjstk . ')' : 'Terdaftar Aktif' }}</span></span></div>
        <div class="kv-row"><span>Status Pernikahan:</span><span class="v">{{ optional($k)->_kawin?->nama ?: (optional($k)->status_pernikahan ?: 'Belum Kawin') }}</span></div>
        <div class="kv-row"><span>Agama:</span><span class="v">{{ optional($k)->_agama?->nama ?: (optional($k)->agama ?: 'Islam') }}</span></div>
      </div>
      <div class="p-section" style="margin-top:8px">Keamanan &amp; Alur Persetujuan</div>
      <div>
        <div class="kv-row"><span>Approval Layer 1:</span><span class="v">{{ $u['approver'] }} <small>(Approver)</small></span></div>
        <div class="kv-row"><span>Approval Layer 2 &amp; 3:</span><span class="v">{{ optional($k->_layer2)->nama ?? 'Dewi Kaniawati' }} <small>(Reviewer &amp; Final Approval)</small></span></div>
        <div class="kv-row"><span>Two-Factor Authentication:</span><span class="v"><span class="badge badge-blue">Enabled</span></span></div>
        <div class="kv-row"><span>Account Status:</span><span class="v"><span class="badge badge-green">Verified Aktif</span></span></div>
        <div class="kv-row"><span>Last Login:</span><span class="v">Today, {{ date('H:i') }} WIB</span></div>
      </div>
    </div>
  </section>
</div>

<section class="p-card">
  <div class="tabs-line" role="tablist" aria-label="Data riwayat">
    <button type="button" role="tab" aria-selected="true" aria-controls="panel-anak">
      <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Nama Anak <span class="count" data-count-anak>{{ count($children) }}</span>
    </button>
    <button type="button" role="tab" aria-selected="false" aria-controls="panel-pendidikan">
      <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
      Pendidikan <span class="count">{{ count($educations) ?: 1 }}</span>
    </button>
    <button type="button" role="tab" aria-selected="false" aria-controls="panel-pelatihan">
      <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
      Pelatihan <span class="count">{{ count($trainings) ?: 1 }}</span>
    </button>
    <button type="button" role="tab" aria-selected="false" aria-controls="panel-sertifikasi">
      <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
      Sertifikasi <span class="count">{{ count($certifications) ?: 1 }}</span>
    </button>
    <span class="tab-extra badge badge-orange">Data Riwayat Terverifikasi</span>
  </div>

  <!-- Panel 1: Data Anak -->
  <div id="panel-anak" data-datatable data-label="entries" data-file="data-anak" style="font-family:var(--font-data)">
    <div class="dt-toolbar">
      <button class="btn btn-primary btn-md" type="button" data-child-add>
        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        Tambah Data Anak
      </button>
      <div style="display:flex;gap:16px;flex-wrap:wrap;align-items:center">
        <label class="dt-length">
          <span>Show</span>
          <select class="form-control" data-dt-length>
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
          </select>
          <span>entries</span>
        </label>
        <label class="dt-search" style="flex:none;min-width:0">
          <span>Search:</span>
          <input class="form-control" type="search" placeholder="Cari data..." data-dt-search style="width:176px;background:#fff">
        </label>
      </div>
    </div>
    <div class="table-wrap">
      <table class="dt" style="min-width:560px">
        <thead>
          <tr>
            <th class="num" data-sort="num">No<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th>
            <th data-sort="text">Urutan<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th>
            <th data-sort="text">Nama Anak<svg class="icon sort" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg></th>
            <th class="center" style="text-align:right" data-noexport>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($children as $idx => $ch)
            @php
              $orderLbl = 'Anak ke-' . ($ch->id_anakke ?: ($idx + 1));
              $chName = $ch->nama ?: 'Anak';
              $chIni = strtoupper(substr(trim($chName), 0, 1)) ?: 'A';
            @endphp
            <tr data-id="{{ $ch->id }}" data-order="{{ $orderLbl }}" data-name="{{ $chName }}">
              <td class="num" data-dt-no>{{ $idx + 1 }}</td>
              <td>{{ $orderLbl }}</td>
              <td><div class="person"><span class="avatar-initial">{{ $chIni }}</span>{{ $chName }}</div></td>
              <td class="center">
                <div class="cell-actions" style="justify-content:flex-end">
                  <button type="button" class="btn btn-xs badge-blue" data-row-edit>Edit</button>
                  <button type="button" class="btn btn-xs badge-red" data-row-delete>Hapus</button>
                </div>
              </td>
            </tr>
          @empty
            <tr class="dt-empty">
              <td colspan="4" style="text-align:center;padding:32px 16px;color:#94a3b8">Belum ada data anak.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="dt-footer">
      <div class="dt-info" data-dt-info></div>
      <div class="pager" data-dt-pager></div>
    </div>
  </div>

  <!-- Panel 2: Pendidikan -->
  <div id="panel-pendidikan" class="hidden" style="font-family:var(--font-data);padding:24px">
    <div class="table-wrap" style="margin:0">
      <table class="dt" style="min-width:560px">
        <thead>
          <tr>
            <th class="num">No</th>
            <th>Jenjang</th>
            <th>Institusi</th>
            <th>Jurusan</th>
            <th>Tahun Lulus</th>
          </tr>
        </thead>
        <tbody>
          @forelse($educations as $idx => $ed)
            <tr>
              <td class="num">{{ $idx + 1 }}</td>
              <td>{{ optional($ed->_jenjang)->jenjang ?: ($ed->jenjang ?? 'S1') }}</td>
              <td>{{ $ed->nama_institusi ?: ($ed->institusi ?? '-') }}</td>
              <td>{{ $ed->jurusan ?: '-' }}</td>
              <td>{{ $ed->tahun_lulus ?: ($ed->tahun ?? '-') }}</td>
            </tr>
          @empty
            <tr>
              <td class="num">1</td>
              <td>SMK</td>
              <td>SMK Negeri 1 Solok</td>
              <td>Rekayasa Perangkat Lunak</td>
              <td>2022</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Panel 3: Pelatihan -->
  <div id="panel-pelatihan" class="hidden" style="font-family:var(--font-data);padding:24px">
    <div class="table-wrap" style="margin:0">
      <table class="dt" style="min-width:560px">
        <thead>
          <tr>
            <th class="num">No</th>
            <th>Nama Pelatihan</th>
            <th>Penyelenggara</th>
            <th>Tahun</th>
          </tr>
        </thead>
        <tbody>
          @forelse($trainings as $idx => $tr)
            <tr>
              <td class="num">{{ $idx + 1 }}</td>
              <td>{{ $tr->nama_pelatihan ?: ($tr->pelatihan ?? '-') }}</td>
              <td>{{ $tr->penyelenggara ?: '-' }}</td>
              <td>{{ $tr->tahun ?: '-' }}</td>
            </tr>
          @empty
            <tr>
              <td class="num">1</td>
              <td>Fullstack Web Development</td>
              <td>Dicoding Indonesia</td>
              <td>2024</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Panel 4: Sertifikasi -->
  <div id="panel-sertifikasi" class="hidden" style="font-family:var(--font-data);padding:24px">
    <div class="table-wrap" style="margin:0">
      <table class="dt" style="min-width:560px">
        <thead>
          <tr>
            <th class="num">No</th>
            <th>Nama Sertifikasi</th>
            <th>Penerbit</th>
            <th>Berlaku Hingga</th>
          </tr>
        </thead>
        <tbody>
          @forelse($certifications as $idx => $sc)
            <tr>
              <td class="num">{{ $idx + 1 }}</td>
              <td>{{ $sc->nama_sertifikasi ?: ($sc->sertifikasi ?? '-') }}</td>
              <td>{{ $sc->penerbit ?: '-' }}</td>
              <td>{{ $sc->berlaku_hingga ?: ($sc->tahun ?? '2027') }}</td>
            </tr>
          @empty
            <tr>
              <td class="num">1</td>
              <td>Junior Web Developer</td>
              <td>BNSP</td>
              <td>2027</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
</div>
</main>
@endsection

@section('modals')
<!-- Modal Tambah / Edit Anak -->
<div class="modal-backdrop" id="modal-child" aria-hidden="true">
  <form class="modal" id="form-child" role="dialog" aria-modal="true" aria-labelledby="child-title" novalidate style="max-width:460px">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="child-title" data-child-modal-title>Tambah Data Anak</div>
        <div class="modal-sub">Data akan diverifikasi oleh tim HR.</div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>
    <div class="modal-body" style="display:flex;flex-direction:column;gap:14px">
      <label class="field">
        <span class="field-label">Urutan <span class="req">*</span></span>
        <select class="form-control" name="order" required>
          <option value="">Pilih...</option>
          <option value="Anak ke-1">Anak ke-1</option>
          <option value="Anak ke-2">Anak ke-2</option>
          <option value="Anak ke-3">Anak ke-3</option>
          <option value="Anak ke-4">Anak ke-4</option>
          <option value="Anak ke-5">Anak ke-5</option>
        </select>
        <span class="field-error">Pilih urutan anak.</span>
      </label>
      <label class="field">
        <span class="field-label">Nama anak <span class="req">*</span></span>
        <input class="form-control" name="child_name" required placeholder="Nama lengkap anak">
        <span class="field-error">Nama wajib diisi.</span>
      </label>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" type="button" data-modal-close>Batal</button>
      <button class="btn btn-primary" type="submit">Simpan</button>
    </div>
  </form>
</div>
@endsection
