<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Home') — MATTA</title>
  <link rel="icon" type="image/png" href="{{ asset('matta-ui/img/favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('matta-ui/css/style.css') }}?v={{ file_exists(public_path('matta-ui/css/style.css')) ? filemtime(public_path('matta-ui/css/style.css')) : time() }}">
  @stack('styles')
  @php
    $mattaUserJs = [
      'name' => $u['name'] ?? 'M. Daffa Dzaky Pratama',
      'nip' => $u['nip'] ?? 'P0826155',
      'initials' => $u['initials'] ?? 'DD',
      'email' => $u['email'] ?? 'daffa.dzaky@alita.id',
      'role' => $u['role'] ?? 'Frontend Developer',
      'unit' => $u['unit'] ?? 'Infrastructure & Security',
      'company' => $u['company'] ?? 'PT Alita Praya Mitra',
      'directorate' => $u['directorate'] ?? 'Engineering',
      'division' => $u['division'] ?? 'Operations',
      'department' => $u['department'] ?? 'IT Workplace',
      'wg' => $u['wg'] ?? 'Reguler - 0830-1730',
      'approver' => $u['approver'] ?? 'Muhammad Rizky',
      'approver_nip' => $u['approver_nip'] ?? '01031502441'
    ];
    $mattaRoutesJs = [
      'dashboard' => route('matta.dashboard'),
      'clock' => route('matta.clock'),
      'resetClock' => route('matta.clock.reset'),
      'feeds' => route('matta.feeds'),
      'feedDetail' => route('matta.feed.detail'),
      'health' => route('matta.health'),
      'submitHealth' => route('matta.health.submit'),
      'payroll' => route('matta.payroll'),
      'attendanceLog' => route('matta.attendance.log'),
      'attendanceRecap' => route('matta.attendance.recap'),
      'propose' => route('matta.propose'),
      'submitPropose' => route('matta.propose.submit'),
      'profile' => route('matta.profile'),
      'updateProfile' => route('matta.profile.update'),
      'storeChild' => route('matta.profile.child.store'),
      'deleteChild' => route('matta.profile.child.delete'),
      'permit' => route('matta.form', 'permit'),
      'exitClearance' => route('matta.form', 'exit-clearance'),
      'submitModule' => route('matta.form.submit')
    ];
  @endphp
  <script>
    window.MATTA_ASSET_URL = {!! json_encode(asset('matta-ui')) !!};
    window.MATTA_ROUTES = {!! json_encode($mattaRoutesJs) !!};
    window.MATTA_USER = {!! json_encode($mattaUserJs) !!};
  </script>
  @stack('head_scripts')
</head>
<body data-ui="v1" data-ui-kind="@yield('body_ui_kind', 'home')" @if(View::hasSection('body_module')) data-module="@yield('body_module')" @endif>
<div class="page-loader" aria-hidden="true"></div>
<div class="app">

  <!-- ==================== SIDEBAR ==================== -->
  <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
    <div class="sidebar-brand">
      <a href="{{ route('matta.dashboard') }}" class="brand" aria-label="MATTA beranda">
        <img class="brand-logo" src="{{ asset('matta-ui/img/logo-matta.png') }}" alt="" width="36" height="36">
      </a>
      <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-controls="sidebar" aria-expanded="true" aria-label="Buka/tutup menu">
        <svg class="icon icon-md" viewBox="0 0 24 24" aria-hidden="true"><line x1="21" x2="3" y1="6" y2="6"/><line x1="15" x2="3" y1="12" y2="12"/><line x1="17" x2="3" y1="18" y2="18"/></svg>
      </button>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-group">
        <span class="nav-label" data-i18n="nav.home_group">HOME PAGE</span>
        <ul class="nav-list">
          <li>
            <a class="nav-link {{ ($activeNav ?? '') === 'dashboard' ? 'active' : '' }}" {{ ($activeNav ?? '') === 'dashboard' ? 'aria-current="page"' : '' }} href="{{ route('matta.dashboard') }}">
              <svg class="icon icon-fill" viewBox="0 0 24 24" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
              <span data-i18n="nav.home">Home</span>
            </a>
          </li>
          <li>
            <a class="nav-link {{ ($activeNav ?? '') === 'feeds' ? 'active' : '' }}" href="{{ route('matta.feeds') }}">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
              <span data-i18n="nav.feeds">Feeds</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="nav-group">
        <span class="nav-label" data-i18n="nav.apps">APPLICATIONS</span>
        <ul class="nav-list">
          <li>
            <button type="button" class="nav-link" data-submenu-toggle aria-controls="sub-forms" aria-expanded="{{ str_starts_with($activeNav ?? '', 'form-') ? 'true' : 'false' }}">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 18v-2"/><path d="M12 18v-6"/><path d="M16 18v-4"/></svg>
              <span data-i18n="nav.forms">Forms</span>
              <svg class="icon chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <ul class="nav-sub" id="sub-forms">
              <div>
                <li><a href="{{ route('matta.form', 'overtime') }}" class="{{ ($activeNav ?? '') === 'form-overtime' ? 'active' : '' }}">Overtime</a></li>
                <li><a href="{{ route('matta.form', 'evaluation') }}" class="{{ ($activeNav ?? '') === 'form-evaluation' ? 'active' : '' }}">Evaluation</a></li>
                <li><a href="{{ route('matta.form', 'leave') }}" class="{{ ($activeNav ?? '') === 'form-leave' ? 'active' : '' }}">Leave</a></li>
                <li><a href="{{ route('matta.form', 'permit') }}" class="{{ ($activeNav ?? '') === 'form-permit' ? 'active' : '' }}">Permit</a></li>
                <li><a href="{{ route('matta.form', 'exit-clearance') }}" class="{{ ($activeNav ?? '') === 'form-exit-clearance' ? 'active' : '' }}">Exit Clearance</a></li>
                <li><a href="{{ route('matta.form', 'reimbursement') }}" class="{{ ($activeNav ?? '') === 'form-reimbursement' ? 'active' : '' }}">Reimbursement</a></li>
                <li><a href="{{ route('matta.form', 'sick-leave') }}" class="{{ ($activeNav ?? '') === 'form-sick-leave' ? 'active' : '' }}">Sick Leave</a></li>
                <li><a href="{{ route('matta.form', 'confirmation') }}" class="{{ ($activeNav ?? '') === 'form-confirmation' ? 'active' : '' }}">Confirmation</a></li>
              </div>
            </ul>
          </li>
        </ul>
      </div>

      <div class="nav-group">
        <span class="nav-label" data-i18n="nav.payroll_group">PAYROLL</span>
        <ul class="nav-list">
          <li>
            <a class="nav-link {{ ($activeNav ?? '') === 'payroll' ? 'active' : '' }}" href="{{ route('matta.payroll') }}">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
              <span data-i18n="nav.payroll">Payroll Slip</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="nav-group">
        <span class="nav-label" data-i18n="nav.att_group">ATTENDANCE</span>
        <ul class="nav-list">
          <li>
            <a class="nav-link {{ ($activeNav ?? '') === 'attendance-log' ? 'active' : '' }}" href="{{ route('matta.attendance.log') }}">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span data-i18n="nav.att_log">Attendance Log</span>
            </a>
          </li>
          <li>
            <a class="nav-link {{ ($activeNav ?? '') === 'attendance-recap' ? 'active' : '' }}" href="{{ route('matta.attendance.recap') }}">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span data-i18n="nav.att_recap">Attendance Recap</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="nav-group">
        <span class="nav-label" data-i18n="nav.ot_group">OVERTIME</span>
        <ul class="nav-list">
          <li>
            <a class="nav-link {{ ($activeNav ?? '') === 'propose' ? 'active' : '' }}" href="{{ route('matta.propose') }}">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span data-i18n="nav.propose">Propose</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="sidebar-footer">
      <img src="{{ asset('matta-ui/img/logo-trust.png') }}" alt="TRUST - Truthful Resilient Unwavering Sound Teamwork" class="sidebar-footer-logo" width="176" height="53">
    </div>
  </aside>

  <div class="sidebar-overlay" aria-hidden="true"></div>

  <!-- ==================== APP MAIN ==================== -->
  <div class="app-main">
    <header class="app-header">
      <div class="header-left">
        <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-controls="sidebar" aria-expanded="true" aria-label="Buka/tutup menu">
          <svg class="icon icon-md" viewBox="0 0 24 24" aria-hidden="true"><line x1="21" x2="3" y1="6" y2="6"/><line x1="15" x2="3" y1="12" y2="12"/><line x1="17" x2="3" y1="18" y2="18"/></svg>
        </button>
        <span class="header-brand">
          <a href="{{ route('matta.dashboard') }}" class="brand" aria-label="MATTA beranda">
            <img class="brand-logo" src="{{ asset('matta-ui/img/logo-matta.png') }}" alt="" width="36" height="36">
          </a>
        </span>
      </div>

      <div class="header-actions">
        @can('hc')
        <a href="{{ route('todolist.index') }}" class="portal-switch-btn" title="Beralih ke HC Admin Portal" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fff7ed;color:#ea580c;border:1px solid #fdba74;border-radius:9999px;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s ease;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
          <span>HC Admin Portal</span>
        </a>
        @endcan
        <div class="lang-pill" role="group" aria-label="Bahasa">
          <button type="button" data-lang="en" aria-pressed="true">EN</button>
          <button type="button" data-lang="id" aria-pressed="false">ID</button>
        </div>
        <div class="profile">
          <button type="button" class="profile-badge" data-dropdown="menu-profile" aria-haspopup="true" aria-expanded="false">
            <div>
              <div class="profile-name">{{ $u['name'] ?? 'M. Daffa Dzaky Pratama' }}</div>
              <div class="profile-nip">{{ $u['nip'] ?? 'P0826155' }}</div>
            </div>
            <span class="avatar-btn">
              <svg class="icon icon-md" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
          </button>
          <div class="dropdown" id="menu-profile" role="menu">
            <a href="{{ route('matta.profile') }}" role="menuitem">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <span data-i18n="menu.profile">My Profile</span>
            </a>
            @can('hc')
            <a href="{{ route('todolist.index') }}" role="menuitem">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
              <span>HC Admin Portal</span>
            </a>
            @endcan
            <hr>
            <form action="{{ route('logoutapp') }}" method="POST" id="matta-logout-form" style="display:none;">@csrf</form>
            <button type="button" class="danger" role="menuitem" onclick="document.getElementById('matta-logout-form').submit();">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
              <span data-i18n="menu.logout">Log out</span>
            </button>
          </div>
        </div>
      </div>
    </header>

    @yield('content')

  </div> <!-- /.app-main -->
</div> <!-- /.app -->

<!-- Drawer Detail Modal -->
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

<!-- Modal Apply -->
<div class="modal-backdrop" id="modal-apply" aria-hidden="true">
  <form class="modal" id="form-apply" role="dialog" aria-modal="true" aria-labelledby="apply-title" novalidate>
    <div class="modal-header"><div><div class="modal-title" id="apply-title" data-apply-title>Ajukan</div><div class="modal-sub">Isi data pengajuan, lalu kirim untuk persetujuan atasan.</div></div><button class="icon-btn" type="button" data-modal-close aria-label="Tutup"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button></div>
    <div class="modal-body"><div class="form-grid" data-fieldset="default">
      <label class="field"><span class="field-label">Tanggal mulai <span class="req">*</span></span><input class="form-control" type="date" name="start" required><span class="field-error">Tanggal mulai wajib diisi.</span></label>
      <label class="field"><span class="field-label">Tanggal selesai</span><input class="form-control" type="date" name="end"></label>
      <label class="field span-2"><span class="field-label">Keterangan <span class="req">*</span></span><textarea class="form-control" name="note" placeholder="Tuliskan alasan / keterangan pengajuan" required></textarea><span class="field-error">Keterangan wajib diisi.</span></label>
      <label class="field span-2"><span class="field-label">Lampiran (PDF/JPG, opsional)</span><input class="form-control" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" style="padding:6px 10px;height:auto"></label>
    </div>
    <div class="form-grid sick-grid" data-fieldset="sick-leave" hidden>
      <label class="field"><span class="field-label">Pengaju</span><input class="form-control" value="{{ $u['name'] ?? 'M. Daffa Dzaky Pratama' }} - {{ $u['nip'] ?? 'P0826155' }}" readonly tabindex="-1" data-no-i18n></label>
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
    <div class="modal-footer"><button class="btn btn-outline" type="button" data-modal-close>Batal</button><button class="btn btn-primary" type="submit"><svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>Kirim Pengajuan</button></div>
  </form>
</div>

<script src="{{ asset('matta-ui/js/i18n.js') }}"></script>
<script src="{{ asset('matta-ui/js/app.js') }}?v={{ file_exists(public_path('matta-ui/js/app.js')) ? filemtime(public_path('matta-ui/js/app.js')) : time() }}"></script>
@yield('modals')
@stack('scripts')
</body>
</html>