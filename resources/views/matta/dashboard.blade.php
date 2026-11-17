@extends('matta.layouts.app', ['activeNav' => 'dashboard'])

@section('title', 'Home')
@section('body_ui_kind', 'home')

@push('head_scripts')
<script>
  window.MATTA_INITIAL_ATT = @json($todayAtt);
  window.MATTA_ATTENDED_DATES = @json($attendedDates ?? []);
  window.MATTA_LEAVE_DATES = @json($leaveDates ?? []);
  window.MATTA_SICK_DATES = @json($sickDates ?? []);
</script>
@endpush

@push('styles')
<style>
  /* Enhanced Smooth Info Carousel Motion */
  .info-carousel-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0 16px;
  }
  .info-carousel-viewport {
    width: 100%;
    overflow: hidden;
    border-radius: 22px;
  }
  .info-carousel-track {
    display: flex;
    transition: transform .65s cubic-bezier(0.16, 1, 0.3, 1) !important;
    width: 100%;
    will-change: transform;
  }
  .info-slide {
    flex: 0 0 100%;
    min-width: 100%;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    box-sizing: border-box;
  }
  .info-car-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    display: grid;
    place-items: center;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 999px;
    color: #64748b;
    cursor: pointer;
    z-index: 5;
    padding: 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all .25s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .info-car-btn.prev { left: -10px; }
  .info-car-btn.next { right: -10px; }
  .info-car-btn:hover {
    color: #ea580c;
    border-color: #fdba74;
    background: #fffaf5;
    box-shadow: 0 4px 16px rgba(234, 88, 12, 0.2);
    transform: translateY(-50%) scale(1.1);
  }
  .info-car-btn:active {
    transform: translateY(-50%) scale(0.92);
  }
  .info-card {
    transition: transform .28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .28s ease, border-color .28s ease !important;
    border-radius: 22px;
  }
  .info-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08) !important;
    border-color: #cbd5e1 !important;
  }
  .info-card-media {
    border-radius: 16px;
    overflow: hidden;
  }
  .info-card-media img {
    transition: transform .5s cubic-bezier(0.16, 1, 0.3, 1) !important;
  }
  .info-card:hover .info-card-media img {
    transform: scale(1.05) !important;
  }
  .info-dots-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
  }
  .info-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #cbd5e1;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all .35s cubic-bezier(0.16, 1, 0.3, 1) !important;
  }
  .info-dot:hover {
    background: #94a3b8;
    transform: scale(1.25);
  }
  .info-dot.active {
    width: 24px;
    background: #ea580c !important;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35);
    transform: none !important;
  }
</style>
@endpush

@section('content')
<main class="main main-dashboard" id="main">
<div class="page-top">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('matta.dashboard') }}">Home</a>
    <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
    <span class="current" aria-current="page" data-crumb-current>Dashboard</span>
  </nav>
</div>

<div data-page="dashboard" class="dashboard-content">
  <div class="dash-greet-wrap">
    <h1 class="greeting"><span data-greeting>Good Morning</span>, <span class="accent">{{ $u['name'] ? (explode(' ', $u['name'])[1] ?? explode(' ', $u['name'])[0]) : 'Daffa' }}</span></h1>
    <p class="today"><span data-today>{{ now()->format('l, j F Y') }}</span></p>
  </div>

  <section class="dash-top">
    <!-- Card 1: Attendance Card -->
    @php
      $hasClockedIn = !empty($todayAtt['in']);
      $hasClockedOut = !empty($todayAtt['out']);
      $inTimeFmt = $hasClockedIn ? \Carbon\Carbon::createFromTimestampMs($todayAtt['in'])->format('H:i') : '--:--';
      $outTimeFmt = $hasClockedOut ? \Carbon\Carbon::createFromTimestampMs($todayAtt['out'])->format('H:i') : '--:--';
    @endphp
    <div class="card attend-card-v4 {{ !$hasClockedIn ? 'pending' : ($hasClockedOut ? 'done' : '') }}" data-attend-card>
      <div class="att-head">
        <div class="att-clock-row">
          <span class="att-big-time" data-clock>{{ now()->format('H:i') }}</span>
          <span class="att-wib">WIB</span>
        </div>
        <button type="button" class="icon-btn att-reset" data-att-reset title="Reset presensi (demo)" aria-label="Reset presensi (demo)">
          <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </button>
      </div>
      <div class="att-record-box">
        <div class="att-record-label" data-attend-title>{{ $hasClockedOut ? 'CLOCK OUT TERCATAT' : 'CLOCK IN TERCATAT' }}</div>
        <div class="att-record-time-row">
          <span class="att-record-time" data-attend-time>{{ $hasClockedOut ? $outTimeFmt : $inTimeFmt }}</span>
          <span class="att-record-tz">WIB</span>
        </div>
        <div class="att-record-status" data-attend-badge>{{ !$hasClockedIn ? 'Belum Absen' : ($hasClockedOut ? 'Selesai' : 'Tepat Waktu') }}</div>
      </div>
      <button type="button" class="btn btn-clock-out {{ !$hasClockedIn ? 'in' : ($hasClockedOut ? 'done' : 'out') }}" data-attend-cta data-state="{{ !$hasClockedIn ? 'in' : ($hasClockedOut ? 'done' : 'out') }}" {{ $hasClockedOut ? 'disabled' : '' }}>
        <svg class="icon-clock-out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
          <polyline points="10 17 5 12 10 7"></polyline>
          <line x1="5" y1="12" x2="15" y2="12"></line>
        </svg>
        <span data-attend-cta-label>{{ !$hasClockedIn ? 'Clock In' : ($hasClockedOut ? 'Selesai Hari Ini' : 'Clock Out') }}</span>
      </button>
    </div>

    <!-- Card 2: Calendar Card -->
    <div class="card calendar-v4">
      <div>
        <div class="cal-head">
          <div class="cal-title-wrap">
            <div class="cal-ico-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="3"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
            </div>
            <div>
              <div class="cal-title" data-cal-title>{{ now()->format('F Y') }}</div>
              <div class="cal-sub">Attendance Calendar</div>
            </div>
          </div>
          <div class="cal-nav">
            <button type="button" data-cal-prev aria-label="Bulan sebelumnya">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" data-cal-next aria-label="Bulan berikutnya">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </button>
          </div>
        </div>
        <div class="cal-dow-grid">
          <div class="cal-dow-cell cal-dow-sunday">MIN</div>
          <div class="cal-dow-cell">SEN</div>
          <div class="cal-dow-cell">SEL</div>
          <div class="cal-dow-cell">RAB</div>
          <div class="cal-dow-cell">KAM</div>
          <div class="cal-dow-cell">JUM</div>
          <div class="cal-dow-cell">SAB</div>
        </div>
        <div class="cal-days-grid" data-cal-grid></div>
      </div>
      <div class="cal-summary-row">
        <div class="cal-stat-card">
          <div class="cal-stat-val c-green" data-cal-present>2</div>
          <div class="cal-stat-lbl">Present</div>
        </div>
        <div class="cal-stat-card">
          <div class="cal-stat-val c-orange" data-cal-alpha>0</div>
          <div class="cal-stat-lbl">Alpha</div>
        </div>
        <div class="cal-stat-card">
          <div class="cal-stat-val c-blue" data-cal-cuti>0</div>
          <div class="cal-stat-lbl">Leave</div>
        </div>
        <div class="cal-stat-card">
          <div class="cal-stat-val c-amber" data-cal-sakit>0</div>
          <div class="cal-stat-lbl">Sakit</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Container Info -->
  <section class="card info-container" aria-labelledby="info-sec-title">
    <div class="info-container-head">
      <h2 id="info-sec-title">Info & Pengumuman</h2>
    </div>
    <div class="info-container-body">
      <div class="info-carousel-wrapper">
        <button type="button" class="info-car-btn prev" data-info-prev aria-label="Sebelumnya">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m15 18-6-6 6-6"/>
          </svg>
        </button>

        <div class="info-carousel-viewport">
          <div class="info-carousel-track" data-info-track>
            <!-- Slide 1 (Matching screenshot exactly) -->
            <div class="info-slide">
              <a href="{{ route('matta.feed.detail') }}?id=batik-nasional" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/batik-nasional.jpg') }}" alt="Batik Nasional" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMATION</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">BATIK NASIONAL</h3>
                </div>
              </a>

              <a href="{{ route('matta.feed.detail') }}?id=ulang-tahun-alita" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/ulang-tahun-alita.jpg') }}" alt="Ulang Tahun Alita" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMATION</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">ULANG TAHUN ALITA</h3>
                </div>
              </a>

              <a href="{{ route('matta.feed.detail') }}?id=maulid-nabi" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/maulid-nabi.jpg') }}" alt="Maulid Nabi" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMATION</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">MAULID NABI</h3>
                </div>
              </a>
            </div>

            <!-- Slide 2 -->
            <div class="info-slide">
              <a href="{{ route('matta.feed.detail') }}?id=happy-new-year" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/happy-new-year.jpg') }}" alt="Happy New Year" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMASI</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">HAPPY NEW YEAR</h3>
                </div>
              </a>

              <a href="{{ route('matta.feed.detail') }}?id=pengumuman-penting" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/pengumuman-penting.jpg') }}" alt="Pengumuman Penting" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">PENGUMUMAN</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">PENGUMUMAN PENTING</h3>
                </div>
              </a>

              <a href="{{ route('matta.feed.detail') }}?id=libur-nasional" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/libur-nasional.jpg') }}" alt="Libur Nasional" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMASI</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">LIBUR NASIONAL</h3>
                </div>
              </a>
            </div>

            <!-- Slide 3 -->
            <div class="info-slide">
              <a href="{{ route('matta.feed.detail') }}?id=maintenance-sistem-hris" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/pengumuman-penting.jpg') }}" alt="Maintenance Sistem HRIS" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">PENGUMUMAN</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">MAINTENANCE HRIS</h3>
                </div>
              </a>

              <a href="{{ route('matta.feed.detail') }}?id=cuti-bersama-idul-fitri" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/libur-nasional.jpg') }}" alt="Cuti Bersama Idul Fitri" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMASI</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">CUTI BERSAMA</h3>
                </div>
              </a>

              <a href="{{ route('matta.feed.detail') }}?id=gathering-akhir-tahun-2025" class="info-card">
                <div class="info-card-media">
                  <img src="{{ asset('matta-ui/img/feeds/happy-new-year.jpg') }}" alt="Gathering Akhir Tahun" loading="lazy">
                </div>
                <div class="info-card-body">
                  <span class="info-badge">INFORMASI</span>
                  <div class="info-divider"></div>
                  <h3 class="info-title">GATHERING TAHUNAN</h3>
                </div>
              </a>
            </div>
          </div>
        </div>

        <button type="button" class="info-car-btn next" data-info-next aria-label="Berikutnya">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 18 6-6-6-6"/>
          </svg>
        </button>
      </div>

      <div class="info-dots-wrap" data-info-dots>
        <button type="button" class="info-dot active" data-dot="0" aria-label="Slide 1"></button>
        <button type="button" class="info-dot" data-dot="1" aria-label="Slide 2"></button>
        <button type="button" class="info-dot" data-dot="2" aria-label="Slide 3"></button>
      </div>
    </div>
  </section>

  <!-- Section: Employee Activities -->
  <section class="card activity" aria-labelledby="act-title">
    <div class="activity-head">
      <h2 id="act-title" data-i18n="dash.activity">Employee Activities</h2>
    </div>
    <div class="activity-body">
      <div class="module-grid">
        <!-- 1. OVERTIME -->
        <button type="button" class="module-card" data-module="overtime" data-module-card="overtime" aria-pressed="false" style="--i:0">
          <span class="m-head"><span class="m-name">OVERTIME</span></span>
          <span class="m-corner-badge">{{ count($modules['overtime']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/overtime.png') }}" alt="" loading="lazy">
        </button>

        <!-- 2. EVALUASI -->
        <button type="button" class="module-card" data-module="evaluation" data-module-card="evaluation" aria-pressed="false" style="--i:1">
          <span class="m-head"><span class="m-name">EVALUASI</span></span>
          <span class="m-corner-badge">{{ count($modules['evaluation']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/evaluation.png') }}" alt="" loading="lazy">
        </button>

        <!-- 3. CUTI -->
        <button type="button" class="module-card" data-module="leave" data-module-card="leave" aria-pressed="false" style="--i:2">
          <span class="m-head"><span class="m-name">CUTI</span></span>
          <span class="m-corner-badge">{{ count($modules['leave']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/leave.png') }}" alt="" loading="lazy">
        </button>

        <!-- 4. IZIN -->
        <button type="button" class="module-card" data-module="permit" data-module-card="permit" aria-pressed="false" style="--i:3">
          <span class="m-head"><span class="m-name">IZIN</span></span>
          <span class="m-corner-badge">{{ count($modules['permit']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/permit.png') }}" alt="" loading="lazy">
        </button>

        <!-- 5. IZIN KELUAR (Default selected to match user screenshot) -->
        <button type="button" class="module-card" data-module="exit-clearance" data-module-card="exit-clearance" aria-pressed="true" style="--i:4">
          <span class="m-head"><span class="m-name">IZIN KELUAR</span></span>
          <span class="m-corner-badge">{{ count($modules['exit-clearance']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/exit-clearance.png') }}" alt="" loading="lazy">
        </button>

        <!-- 6. PENGEMBALIAN -->
        <button type="button" class="module-card" data-module="reimbursement" data-module-card="reimbursement" aria-pressed="false" style="--i:5">
          <span class="m-head"><span class="m-name">PENGEMBALIAN</span></span>
          <span class="m-corner-badge">{{ count($modules['reimbursement']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/reimbursement.png') }}" alt="" loading="lazy">
        </button>

        <!-- 7. IZIN SAKIT -->
        <button type="button" class="module-card" data-module="sick-leave" data-module-card="sick-leave" aria-pressed="false" style="--i:6">
          <span class="m-head"><span class="m-name">IZIN SAKIT</span></span>
          <span class="m-corner-badge">{{ count($modules['sick-leave']['rows'] ?? []) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/sick-leave.png') }}" alt="" loading="lazy">
        </button>

        <!-- 8. KONFIRMASI -->
        <button type="button" class="module-card" data-module="confirmation" data-module-card="confirmation" aria-pressed="false" style="--i:7">
          <span class="m-head"><span class="m-name">KONFIRMASI</span></span>
          <span class="m-corner-badge">{{ count($modules['confirmation']['rows'] ?? []) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#f05a00" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="13" y="10" width="26" height="34" rx="5" fill="#fff9f1" stroke="#f05a00"/>
            <rect x="20" y="6" width="12" height="6" rx="2" fill="#fff9f1" stroke="#f05a00"/>
            <line x1="19" y1="20" x2="31" y2="20" stroke="#f05a00"/>
            <line x1="19" y1="26" x2="27" y2="26" stroke="#f05a00"/>
            <circle cx="33" cy="35" r="8" fill="#fff9f1" stroke="#f05a00" stroke-width="2.4"/>
            <polyline points="29.5 35 32 37.5 36.5 32.5" stroke="#f05a00" stroke-width="2.4"/>
          </svg>
        </button>
      </div>

      <!-- DETAIL CONTAINER (Default: exit-clearance as shown in screenshot) -->
      <div class="module-detail" data-module-detail data-module="exit-clearance" aria-live="polite">
        <div class="md-head">
          <div class="md-title-wrap">
            <div class="md-ico" aria-hidden="true" data-md-iconbox>
              <img data-md-img src="{{ asset('matta-ui/img/modules/exit-clearance.png') }}" alt="">
            </div>
            <div>
              <h3 class="md-title" data-md-title>Daftar Izin Keluar</h3>
              <p class="md-desc" data-md-desc>Riwayat &amp; status proses serah terima aset, klirens departemen, dan exit clearance</p>
            </div>
          </div>
          <div class="md-actions">
            <button type="button" class="btn btn-apply-act" data-md-apply>
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
              <span data-md-apply-label>Ajukan Izin Keluar</span>
            </button>
          </div>
        </div>
        <div class="md-body">
          <div class="md-table">
            <div class="scroll">
              <table>
                <thead>
                  <tr>
                    <th>NO</th>
                    <th>ID</th>
                    <th>FORMULIR</th>
                    <th>NAMA KARYAWAN</th>
                    <th>JABATAN</th>
                    <th>UNIT KERJA</th>
                    <th>TANGGAL</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody data-md-body>
                  @php
                    $initRows = $modules['exit-clearance']['rows'] ?? [];
                  @endphp
                  @forelse($initRows as $idx => $r)
                    <tr data-detail="">
                      <td>{{ $idx + 1 }}</td>
                      <td><span class="id-chip">{{ $r['id'] }}</span></td>
                      <td>
                        <div class="doc-name">{{ $r['form'] }}</div>
                        <a class="doc-link" href="#" data-fake-file="{{ $r['file'] }}">
                          <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                          {{ $r['file'] }}
                        </a>
                      </td>
                      <td>
                        <div class="person-name">{{ $r['name'] }}</div>
                        <div class="person-sub mono">{{ $r['nip'] }}</div>
                      </td>
                      <td>{{ $r['role'] }}</td>
                      <td>{{ $r['unit'] }}</td>
                      <td>
                        <div class="doc-name">{{ $r['date'] }}</div>
                        <div class="person-sub">{{ $r['time'] }}</div>
                      </td>
                      <td>
                        @php
                          $stBadge = ($r['status'] === 'Approved') ? 'badge-green' : (($r['status'] === 'Rejected') ? 'badge-red' : 'badge-orange');
                        @endphp
                        <span class="badge badge-pill badge-dot {{ $stBadge }}">{{ $r['status'] }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr class="md-empty">
                      <td colspan="8">Belum ada pengajuan. Klik tombol di atas untuk membuat pengajuan baru.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <div class="md-foot">
              <span data-md-info>
                @if(count($initRows))
                  Showing 1-{{ count($initRows) }} of {{ count($initRows) }} data izin keluar
                @else
                  Showing 0-0 of 0 data izin keluar
                @endif
              </span>
              <div class="pager">
                <button type="button" disabled>Previous</button>
                <button type="button" class="active">1</button>
                <button type="button" disabled>Next</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script type="application/json" id="module-data">{!! json_encode($modules) !!}</script>

<!-- Modal Popup Kartu Dashboard (Menu More / Layanan Lainnya) -->
<div class="modal-backdrop" id="modal-card-popup" aria-hidden="true">
  <div class="modal modal-lg" role="dialog" aria-modal="true" aria-labelledby="card-pop-title">
    <div class="modal-header">
      <div class="card-pop-header">
        <div class="card-pop-icon" data-card-pop-icon>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#f05a00" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:36px;height:36px;">
            <rect x="13" y="10" width="26" height="34" rx="5" fill="#fff9f1" stroke="#f05a00"/>
            <rect x="20" y="6" width="12" height="6" rx="2" fill="#fff9f1" stroke="#f05a00"/>
            <line x1="19" y1="20" x2="31" y2="20" stroke="#f05a00"/>
            <line x1="19" y1="26" x2="27" y2="26" stroke="#f05a00"/>
            <circle cx="33" cy="35" r="8" fill="#fff9f1" stroke="#f05a00" stroke-width="2.4"/>
            <polyline points="29.5 35 32 37.5 36.5 32.5" stroke="#f05a00" stroke-width="2.4"/>
          </svg>
        </div>
        <div>
          <div class="modal-title" id="card-pop-title" data-card-pop-title>Modul Konfirmasi</div>
          <div class="modal-sub" data-card-pop-sub>Klik kartu di bawah ini untuk menampilkan tabel riwayat konfirmasi di dashboard</div>
        </div>
      </div>
      <button class="icon-btn" type="button" data-modal-close aria-label="Tutup"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
    </div>
    <div class="modal-body" data-card-pop-body style="padding: 22px 24px;">
      <div class="pop-single-wrap">
        <!-- Card: Konfirmasi Saja -->
        <button type="button" class="pop-module-card" data-pop-select="confirmation" aria-label="Modul Konfirmasi">
          <span class="m-head"><span class="m-name">KONFIRMASI</span></span>
          <span class="m-corner-badge">{{ count($modules['confirmation']['rows'] ?? []) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#f05a00" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="13" y="10" width="26" height="34" rx="5" fill="#fff9f1" stroke="#f05a00"/>
            <rect x="20" y="6" width="12" height="6" rx="2" fill="#fff9f1" stroke="#f05a00"/>
            <line x1="19" y1="20" x2="31" y2="20" stroke="#f05a00"/>
            <line x1="19" y1="26" x2="27" y2="26" stroke="#f05a00"/>
            <circle cx="33" cy="35" r="8" fill="#fff9f1" stroke="#f05a00" stroke-width="2.4"/>
            <polyline points="29.5 35 32 37.5 36.5 32.5" stroke="#f05a00" stroke-width="2.4"/>
          </svg>
        </button>
      </div>
    </div>
    <div class="card-pop-foot" style="justify-content: flex-end;">
      <button class="btn btn-outline btn-md" type="button" data-modal-close>Tutup</button>
    </div>
  </div>
</div>

</main>
@endsection
