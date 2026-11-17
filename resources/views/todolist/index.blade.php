@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('matta-ui/css/style.css') }}?v={{ file_exists(public_path('matta-ui/css/style.css')) ? filemtime(public_path('matta-ui/css/style.css')) : time() }}">
@endsection

@section('style')
<style>
  /* Seamless match with MATTA User Home Layout */
  .page-title {
    display: none !important;
  }
  .dashboard-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
    padding-top: 24px;
    padding-bottom: 24px;
  }
  .dash-greet-wrap {
    margin-bottom: 4px;
  }
  .dash-greet-wrap .greeting {
    font-size: 26px;
    font-weight: 800;
    color: var(--slate-900, #0f172a);
    letter-spacing: -0.5px;
    margin: 0 0 4px 0;
  }
  .dash-greet-wrap .greeting .accent {
    color: #ff5400;
  }
  .dash-greet-wrap .today {
    font-size: 13.5px;
    color: var(--slate-500, #64748b);
    margin: 0;
  }
  .dash-top {
    display: grid;
    grid-template-columns: 1.15fr 1fr;
    gap: 16px;
    align-items: stretch;
    margin-bottom: 0;
  }
  @media (max-width: 991px) {
    .dash-top {
      grid-template-columns: 1fr;
    }
  }

  /* Info Carousel Styling */
  .info-carousel-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0 16px;
  }
  .info-carousel-viewport {
    width: 100%;
    overflow: hidden;
    border-radius: 20px;
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
    gap: 18px;
    box-sizing: border-box;
  }
  @media (max-width: 768px) {
    .info-slide {
      grid-template-columns: 1fr;
    }
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
  .info-card {
    transition: transform .28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .28s ease, border-color .28s ease !important;
    border-radius: 20px;
    text-decoration: none !important;
    color: inherit;
    display: block;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    cursor: pointer;
  }
  .info-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
    border-color: #fdba74 !important;
  }
  .info-card:hover .info-title {
    color: #ff5400 !important;
    transition: color 0.2s ease;
  }
  .info-card-media {
    position: relative;
    overflow: hidden;
  }
  .info-card-media img {
    transition: transform .45s cubic-bezier(0.16, 1, 0.3, 1) !important;
  }
  .info-card:hover .info-card-media img {
    transform: scale(1.06) !important;
  }
  .info-card-hover-hint {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(15, 23, 42, 0.78);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    opacity: 0;
    transform: translateY(4px);
    transition: all .25s ease;
    pointer-events: none;
  }
  .info-card:hover .info-card-hover-hint {
    opacity: 1;
    transform: translateY(0);
  }
  .feed-content-prose {
    font-size: 14.5px;
    line-height: 1.7;
    color: #334155;
  }
  .feed-content-prose p {
    margin-bottom: 14px;
  }
  .feed-content-prose img {
    max-width: 100%;
    border-radius: 8px;
    margin: 12px 0;
  }

  /* Exact MATTA User Pop-Up Modal Design */
  .matta-pop-backdrop {
    position: fixed !important;
    inset: 0 !important;
    z-index: 999999 !important;
    background: rgba(15, 23, 42, 0.52) !important;
    backdrop-filter: blur(4px) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
    transition: opacity .22s cubic-bezier(.16, 1, .3, 1), visibility .22s ease !important;
  }
  .matta-pop-backdrop.open {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
  }
  .matta-pop-backdrop .matta-pop-modal {
    width: 100% !important;
    max-width: 760px !important;
    max-height: calc(100vh - 48px) !important;
    display: flex !important;
    flex-direction: column !important;
    background: #ffffff !important;
    border-radius: 20px !important;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35) !important;
    border: 1px solid #e2e8f0 !important;
    overflow: hidden !important;
    transform: translateY(14px) scale(.97) !important;
    transition: transform .25s cubic-bezier(.16, 1, .3, 1) !important;
    margin: auto !important;
    position: relative !important;
  }
  .matta-pop-backdrop.open .matta-pop-modal {
    transform: none !important;
  }
  .matta-pop-backdrop .modal-header {
    display: flex !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    gap: 14px !important;
    padding: 20px 26px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    background: #ffffff !important;
  }
  .matta-pop-backdrop .icon-close-pop {
    background: #f1f5f9 !important;
    border: none !important;
    cursor: pointer !important;
    color: #64748b !important;
    width: 36px !important;
    height: 36px !important;
    border-radius: 10px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all .18s ease !important;
    flex-shrink: 0 !important;
    margin-left: 14px !important;
    padding: 0 !important;
  }
  .matta-pop-backdrop .icon-close-pop:hover {
    background: #e2e8f0 !important;
    color: #0f172a !important;
    transform: scale(1.05) !important;
  }
  .matta-pop-backdrop .modal-body {
    padding: 22px 26px !important;
    overflow-y: auto !important;
    flex: 1 1 auto !important;
  }
  .matta-pop-backdrop .modal-footer {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 12px !important;
    padding: 14px 26px !important;
    border-top: 1px solid #f1f5f9 !important;
    background: #f8fafc !important;
  }
  .matta-pop-backdrop .btn-pop-action {
    height: 38px !important;
    padding: 0 16px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    border-radius: 9px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all .18s ease !important;
  }
  .matta-pop-backdrop .btn-pop-outline {
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #475569 !important;
  }
  .matta-pop-backdrop .btn-pop-outline:hover {
    background: #f8fafc !important;
    border-color: #94a3b8 !important;
    color: #1e293b !important;
  }
  .matta-pop-backdrop .btn-pop-orange {
    background: #ffffff !important;
    border: 1px solid #f97316 !important;
    color: #ea580c !important;
  }
  .matta-pop-backdrop .btn-pop-orange:hover {
    background: #fff7ed !important;
    border-color: #ea580c !important;
  }
  .matta-pop-backdrop .btn-pop-primary {
    background: #ff5400 !important;
    border: none !important;
    color: #ffffff !important;
    box-shadow: 0 2px 8px rgba(255, 84, 0, 0.28) !important;
  }
  .matta-pop-backdrop .btn-pop-primary:hover {
    background: #e04a00 !important;
    box-shadow: 0 4px 12px rgba(255, 84, 0, 0.35) !important;
  }
  .info-dots-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 18px;
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
  .info-dot.active {
    width: 24px;
    background: #ea580c !important;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35);
  }

  /* Module Card Overrides for Link */
  a.module-card {
    text-decoration: none !important;
    color: inherit !important;
  }
  a.module-card:hover {
    color: inherit !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -6px rgba(240, 90, 0, 0.18);
    border-color: #f97316;
  }
  a.module-card .m-corner-badge.has-zero {
    background: #f1f5f9;
    color: #94a3b8;
  }
</style>
@endsection

@section('content')
<div class="dashboard-content">

  <!-- 1. GREETING WRAP (Matching User Dashboard) -->
  <div class="dash-greet-wrap">
    <h1 class="greeting">
      <span data-greeting>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}</span>, 
      <span class="accent">{{ auth()->user()->name ? (explode(' ', auth()->user()->name)[1] ?? explode(' ', auth()->user()->name)[0]) : 'Admin' }}</span>
    </h1>
    <p class="today"><span data-today>{{ now()->format('l, j F Y') }}</span></p>
  </div>

  <!-- 2. DASH TOP: ATTENDANCE CARD + CALENDAR CARD (Matching User Dashboard) -->
  <section class="dash-top">

    <!-- Card 1: Attendance / Live Clock Status Card -->
    <div class="card attend-card-v4" data-attend-card>
      <div class="att-head">
        <div class="att-clock-row">
          <span class="att-big-time" id="dash-live-time" data-clock>{{ now()->format('H:i') }}</span>
          <span class="att-wib">WIB</span>
        </div>
        <div class="att-portal-pill">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;display:inline-block;vertical-align:-1px;">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
          <span>ADMIN PORTAL</span>
        </div>
      </div>
      <div class="att-record-box">
        <div class="att-record-label" data-attend-title>STATUS SISTEM</div>
        <div class="att-record-time-row">
          <span class="att-record-time" id="dash-sub-time">{{ now()->format('H:i') }}</span>
          <span class="att-record-tz">WIB</span>
        </div>
        <div class="att-record-status" data-attend-badge>Sistem Aktif</div>
      </div>
      <div class="btn btn-clock-out out" style="cursor: default; pointer-events: none; justify-content: center; gap: 8px;">
        <svg class="icon-clock-out" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;display:inline-block;vertical-align:-2px;">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <span data-attend-cta-label>Mode Persetujuan Siap</span>
      </div>
    </div>

    <!-- Card 2: Calendar Card (Identical User Design & Layout) -->
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
              <div class="cal-title" id="admin-cal-title" data-cal-title>{{ now()->format('F Y') }}</div>
              <div class="cal-sub">Kalender Presensi &bull; {{ now()->format('Y') }}</div>
            </div>
          </div>
          <div class="cal-nav">
            <button type="button" id="btn-admin-cal-prev" aria-label="Bulan sebelumnya">
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" id="btn-admin-cal-next" aria-label="Bulan berikutnya">
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
        <div class="cal-days-grid" id="admin-cal-grid" data-cal-grid></div>
      </div>
    </div>
  </section>

  <!-- 3. CONTAINER INFO CAROUSEL (Identical User Design & Layout) -->
  @php
    $adminFeeds = $feeds ?? \App\Models\Feeds::where('is_active', 1)->orderBy('id', 'desc')->take(9)->get();
    if ($adminFeeds->isEmpty()) {
        $adminFeeds = \App\Models\Feeds::orderBy('id', 'desc')->take(9)->get();
    }

    $availableImages = [
        'batik-nasional.jpg',
        'happy-new-year.jpg',
        'libur-nasional.jpg',
        'maulid-nabi.jpg',
        'pengumuman-penting.jpg',
        'ulang-tahun-alita.jpg',
    ];

    if (!function_exists('resolveFeedImage')) {
        function resolveFeedImage($title, $id, $availableImages) {
            $t = strtolower($title);
            if (str_contains($t, 'batik')) return 'batik-nasional.jpg';
            if (str_contains($t, 'libur') || str_contains($t, 'cuti')) return 'libur-nasional.jpg';
            if (str_contains($t, 'tahun') || str_contains($t, 'year')) return 'happy-new-year.jpg';
            if (str_contains($t, 'maulid') || str_contains($t, 'nabi') || str_contains($t, 'agama') || str_contains($t, 'ramadhan')) return 'maulid-nabi.jpg';
            if (str_contains($t, 'alita') || str_contains($t, 'ultah') || str_contains($t, 'ulang')) return 'ulang-tahun-alita.jpg';
            return $availableImages[$id % count($availableImages)] ?? 'pengumuman-penting.jpg';
        }
    }

    if (!function_exists('resolveFeedTag')) {
        function resolveFeedTag($title) {
            $t = strtolower($title);
            if (str_contains($t, 'libur') || str_contains($t, 'cuti')) return 'HARI LIBUR';
            if (str_contains($t, 'kebijakan') || str_contains($t, 'policy') || str_contains($t, 'aturan') || str_contains($t, 'ketentuan') || str_contains($t, 'bpd') || str_contains($t, 'jam kerja')) return 'HR POLICY';
            if (str_contains($t, 'aplikasi') || str_contains($t, 'mobile') || str_contains($t, 'sistem') || str_contains($t, 'portal') || str_contains($t, 'matta')) return 'APLIKASI';
            if (str_contains($t, 'k3') || str_contains($t, 'kesehatan') || str_contains($t, 'mcu') || str_contains($t, 'safety')) return 'K3 & KESEHATAN';
            if (str_contains($t, 'pengumuman') || str_contains($t, 'edaran') || str_contains($t, 'himbauan')) return 'PENGUMUMAN';
            return 'INFORMASI';
        }
    }

    $feedChunks = $adminFeeds->chunk(3);
  @endphp

  <section class="card info-container" aria-labelledby="info-sec-title">
    <div class="info-container-head d-flex justify-content-between align-items-center">
      <h2 id="info-sec-title" class="m-0">Info & Pengumuman</h2>
      <a href="{{ route('feeds.view') }}" class="btn btn-outline-primary btn-sm" style="border-radius: 9999px; font-weight: 600; font-size: 12px; padding: 5px 14px; text-decoration: none;">
        Lihat Semua Feeds &rarr;
      </a>
    </div>
    <div class="info-container-body">
      <div class="info-carousel-wrapper">
        <button type="button" class="info-car-btn prev" id="info-prev-btn" data-info-prev aria-label="Sebelumnya">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m15 18-6-6 6-6"/>
          </svg>
        </button>

        <div class="info-carousel-viewport">
          <div class="info-carousel-track" id="info-carousel-track" data-info-track>
            @forelse($feedChunks as $slideIdx => $chunk)
              <div class="info-slide">
                @foreach($chunk as $f)
                  @php
                    $feedImg = resolveFeedImage($f->title, $f->id, $availableImages);
                    $feedTag = resolveFeedTag($f->title);
                  @endphp
                  <div class="info-card" role="button" tabindex="0" onclick="openMattaModal('adminFeedModal{{ $f->id }}')" title="Klik untuk melihat detail pengumuman">
                    <div class="info-card-media">
                      <img src="{{ asset('matta-ui/img/feeds/' . $feedImg) }}" alt="{{ $f->title }}" loading="lazy">
                      <span class="info-card-hover-hint">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                          <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Detail
                      </span>
                    </div>
                    <div class="info-card-body">
                      <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="info-badge">{{ $feedTag }}</span>
                        <span class="text-muted" style="font-size: 11px; font-weight: 500;">
                          {{ \Carbon\Carbon::parse($f->created_at)->format('d M Y') }}
                        </span>
                      </div>
                      <div class="info-divider"></div>
                      <h3 class="info-title" title="{{ $f->title }}">{{ Str::limit($f->title, 42) }}</h3>
                    </div>
                  </div>
                @endforeach
              </div>
            @empty
              <div class="info-slide">
                <div class="text-center py-4 w-100 text-muted">Belum ada info & pengumuman aktif.</div>
              </div>
            @endforelse
          </div>
        </div>

        <button type="button" class="info-car-btn next" id="info-next-btn" data-info-next aria-label="Berikutnya">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 18 6-6-6-6"/>
          </svg>
        </button>
      </div>

      <div class="info-dots-wrap" id="info-dots-wrap" data-info-dots>
        @foreach($feedChunks as $slideIdx => $chunk)
          <button type="button" class="info-dot {{ $loop->first ? 'active' : '' }}" data-dot="{{ $slideIdx }}" aria-label="Slide {{ $slideIdx + 1 }}"></button>
        @endforeach
      </div>
    </div>
  </section>

  <!-- MODAL DETAIL FEED POP-UP (SESUAI DESAIN USER POP-UP) -->
  @foreach($adminFeeds as $f)
    @php
      $feedImg = resolveFeedImage($f->title, $f->id, $availableImages);
      $feedTag = resolveFeedTag($f->title);
      $publisher = \App\Http\Controllers\FeedController::getKaryawan($f->created_by);
    @endphp
    <div class="modal-backdrop matta-pop-backdrop" id="adminFeedModal{{ $f->id }}" aria-hidden="true" role="dialog">
      <div class="modal modal-lg matta-pop-modal" role="document">
        
        <!-- Header Pop-up -->
        <div class="modal-header">
          <div class="modal-title-wrap">
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge" style="background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 9999px;">{{ $feedTag }}</span>
              <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 9999px;">Aktif</span>
            </div>
            <h3 class="modal-title" style="font-size: 19px; font-weight: 800; color: #0f172a; line-height: 1.35; margin: 0 0 4px 0;">{{ $f->title }}</h3>
            <p class="modal-sub" style="font-size: 12.5px; color: #64748b; margin: 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
              <span style="display: inline-flex; align-items: center; gap: 5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                </svg>
                {{ $f->created_at }}
              </span>
              <span>&bull;</span>
              <span style="display: inline-flex; align-items: center; gap: 5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
                Dipublikasikan oleh {{ $publisher }}
              </span>
            </p>
          </div>
          <button type="button" class="icon-close-pop" onclick="closeMattaModal('adminFeedModal{{ $f->id }}')" aria-label="Tutup">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Body Pop-up -->
        <div class="modal-body">
          <div style="border-radius: 14px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);">
            <img src="{{ asset('matta-ui/img/feeds/' . $feedImg) }}" alt="{{ $f->title }}" style="width: 100%; max-height: 340px; object-fit: cover; display: block;">
          </div>
          <div class="feed-content-prose">
            {!! $f->content !!}
          </div>
        </div>

        <!-- Footer Pop-up -->
        <div class="modal-footer">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            @can('hc')
            <a href="{{ route('feeds.edit', $f->id) }}" class="btn-pop-action btn-pop-orange">
              <i class="fa fa-pencil"></i> Edit Konten Feed
            </a>
            @endcan
            <a href="{{ route('feeds.view') }}" class="btn-pop-action btn-pop-outline">
              Ke Halaman Feeds &rarr;
            </a>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button class="btn-pop-action btn-pop-outline" type="button" onclick="window.print();">
              <i class="fa fa-print"></i> Cetak
            </button>
            <button class="btn-pop-action btn-pop-primary" type="button" onclick="closeMattaModal('adminFeedModal{{ $f->id }}')">
              Tutup
            </button>
          </div>
        </div>

      </div>
    </div>
  @endforeach

  <!-- 4. SECTION: TO DO LIST / EMPLOYEE ACTIVITIES (Identical User Design & Layout with Admin Database) -->
  <section class="card activity" aria-labelledby="act-title">
    <div class="activity-head">
      <h2 id="act-title">To Do List &bull; Modul Persetujuan</h2>
    </div>
    <div class="activity-body">
      <div class="module-grid">

        <!-- 1. LEMBUR (Overtime) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.lembur0']) }}" class="module-card" data-module="overtime" style="--i:0">
          <span class="m-head"><span class="m-name">LEMBUR</span></span>
          <span class="m-corner-badge {{ count($trxOvertime) > 0 ? '' : 'has-zero' }}">{{ count($trxOvertime) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/overtime.png') }}" alt="" loading="lazy">
        </a>

        <!-- 2. EVALUASI (Evaluation) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.penilaian0']) }}" class="module-card" data-module="evaluation" style="--i:1">
          <span class="m-head"><span class="m-name">EVALUASI</span></span>
          <span class="m-corner-badge {{ count($trxPenilaian) > 0 ? '' : 'has-zero' }}">{{ count($trxPenilaian) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/evaluation.png') }}" alt="" loading="lazy">
        </a>

        <!-- 3. CUTI (Leave) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.cuti0']) }}" class="module-card" data-module="leave" style="--i:2">
          <span class="m-head"><span class="m-name">CUTI</span></span>
          <span class="m-corner-badge {{ count($trxCuti) > 0 ? '' : 'has-zero' }}">{{ count($trxCuti) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/leave.png') }}" alt="" loading="lazy">
        </a>

        <!-- 4. IZIN (Permit) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.izin0']) }}" class="module-card" data-module="permit" style="--i:3">
          <span class="m-head"><span class="m-name">IZIN</span></span>
          <span class="m-corner-badge {{ count($trxIzin) > 0 ? '' : 'has-zero' }}">{{ count($trxIzin) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/permit.png') }}" alt="" loading="lazy">
        </a>

        <!-- 5. IZIN KELUAR (Exit Clearance) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.nonaktif0']) }}" class="module-card" data-module="exit-clearance" style="--i:4">
          <span class="m-head"><span class="m-name">IZIN KELUAR</span></span>
          <span class="m-corner-badge {{ count($trxNonAktif) > 0 ? '' : 'has-zero' }}">{{ count($trxNonAktif) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/exit-clearance.png') }}" alt="" loading="lazy">
        </a>

        <!-- 6. PENGEMBALIAN (Reimbursement) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.reimburse0']) }}" class="module-card" data-module="reimbursement" style="--i:5">
          <span class="m-head"><span class="m-name">PENGEMBALIAN</span></span>
          <span class="m-corner-badge {{ count($trxReimburse) > 0 ? '' : 'has-zero' }}">{{ count($trxReimburse) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/reimbursement.png') }}" alt="" loading="lazy">
        </a>

        <!-- 7. IZIN SAKIT (Sick Leave) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.sakit0']) }}" class="module-card" data-module="sick-leave" style="--i:6">
          <span class="m-head"><span class="m-name">IZIN SAKIT</span></span>
          <span class="m-corner-badge {{ count($trxSakit) > 0 ? '' : 'has-zero' }}">{{ count($trxSakit) }}</span>
          <img class="m-art" src="{{ asset('matta-ui/img/modules/sick-leave.png') }}" alt="" loading="lazy">
        </a>

        <!-- 8. KONFIRMASI (Confirmation) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.karyawan_bulanan0']) }}" class="module-card" data-module="confirmation" style="--i:7">
          <span class="m-head"><span class="m-name">KONFIRMASI</span></span>
          <span class="m-corner-badge {{ count($karyawan_bulanan) > 0 ? '' : 'has-zero' }}">{{ count($karyawan_bulanan) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <rect x="13" y="10" width="26" height="34" rx="5" fill="#fff9f1" stroke="#ff5400"/>
            <rect x="20" y="6" width="12" height="6" rx="2" fill="#fff9f1" stroke="#ff5400"/>
            <line x1="19" y1="20" x2="31" y2="20" stroke="#ff5400"/>
            <line x1="19" y1="26" x2="27" y2="26" stroke="#ff5400"/>
            <circle cx="33" cy="35" r="8" fill="#fff9f1" stroke="#ff5400" stroke-width="2.4"/>
            <polyline points="29.5 35 32 37.5 36.5 32.5" stroke="#ff5400" stroke-width="2.4"/>
          </svg>
        </a>

        @can('asm')
        <!-- 9. REKOMENDASI (Recommendation) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.rekomendasi0']) }}" class="module-card" data-module="recommendation" style="--i:8">
          <span class="m-head"><span class="m-name">REKOMENDASI</span></span>
          <span class="m-corner-badge {{ count($trxRekomendasi) > 0 ? '' : 'has-zero' }}">{{ count($trxRekomendasi) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <circle cx="26" cy="18" r="14" fill="#fff7ed" stroke="#ff5400"/>
            <polyline points="18 30 15 48 26 42 37 48 34 30" stroke="#ff5400" fill="#ffedd5"/>
            <polygon points="26 10 29 16 35 17 31 21 32 27 26 24 20 27 21 21 17 17 23 16 26 10" fill="#ffedd5" stroke="#ff5400"/>
          </svg>
        </a>
        @endcan

        @can('asm')
        <!-- 10. TAMBAH KARYAWAN (Add Employees) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.kebutuhan0']) }}" class="module-card" data-module="kebutuhan" style="--i:9">
          <span class="m-head"><span class="m-name">TAMBAH KARYAWAN</span></span>
          <span class="m-corner-badge {{ count($trxKebutuhan) > 0 ? '' : 'has-zero' }}">{{ count($trxKebutuhan) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <path d="M34 44v-4a8 8 0 0 0-8-8H12a8 8 0 0 0-8 8v4" fill="#fff7ed" stroke="#ff5400"/>
            <circle cx="19" cy="16" r="8" fill="#ffedd5" stroke="#ff5400"/>
            <circle cx="41" cy="23" r="8" fill="#fff7ed" stroke="#ff5400"/>
            <line x1="41" y1="19" x2="41" y2="27" stroke="#ff5400"/>
            <line x1="37" y1="23" x2="45" y2="23" stroke="#ff5400"/>
          </svg>
        </a>
        @endcan

        @can('spv')
        <!-- 11. PELATIHAN (Training) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.pelatihan0']) }}" class="module-card" data-module="training" style="--i:10">
          <span class="m-head"><span class="m-name">PELATIHAN</span></span>
          <span class="m-corner-badge {{ count($trxPelatihan) > 0 ? '' : 'has-zero' }}">{{ count($trxPelatihan) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <path d="M46 22v12M6 22l20-10 20 10-20 10z" fill="#fff7ed" stroke="#ff5400"/>
            <path d="M14 26v10c6 6 18 6 24 0v-10" fill="#ffedd5" stroke="#ff5400"/>
            <circle cx="26" cy="22" r="2.5" fill="#ff5400"/>
          </svg>
        </a>
        @endcan

        @can('bpd')
        <!-- 12. PERJALANAN DINAS (Travel Expense - BPD) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.bpd0']) }}" class="module-card" data-module="bpd" style="--i:11">
          <span class="m-head"><span class="m-name">PERJALANAN DINAS</span></span>
          <span class="m-corner-badge {{ count($trxBpd) > 0 ? '' : 'has-zero' }}">{{ count($trxBpd) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <rect x="6" y="16" width="40" height="28" rx="5" fill="#fff7ed" stroke="#ff5400"/>
            <path d="M32 16V10a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3v6" stroke="#ff5400" fill="none"/>
            <line x1="6" y1="27" x2="46" y2="27" stroke="#fed7aa"/>
            <circle cx="26" cy="27" r="3" fill="#ffedd5" stroke="#ff5400"/>
            <line x1="16" y1="16" x2="16" y2="44" stroke="#fed7aa"/>
            <line x1="36" y1="16" x2="36" y2="44" stroke="#fed7aa"/>
          </svg>
        </a>
        @endcan

        @can('spv')
        <!-- 13. REKRUTMEN (Recruitment) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.recruitment0']) }}" class="module-card" data-module="recruitment" style="--i:12">
          <span class="m-head"><span class="m-name">REKRUTMEN</span></span>
          <span class="m-corner-badge {{ count($recruitment) > 0 ? '' : 'has-zero' }}">{{ count($recruitment) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <path d="M34 44v-4a7 7 0 0 0-7-7H13a7 7 0 0 0-7 7v4" fill="#fff7ed" stroke="#ff5400"/>
            <circle cx="20" cy="17" r="7" fill="#ffedd5" stroke="#ff5400"/>
            <circle cx="36" cy="24" r="9" fill="#fff7ed" stroke="#ff5400"/>
            <line x1="42.5" y1="30.5" x2="47" y2="35" stroke="#ff5400" stroke-width="2.5"/>
          </svg>
        </a>
        @endcan

        @can('notif_letter')
        <!-- 14. SURAT PEMBERITAHUAN (Notification Letter) -->
        <a href="{{ route('todolist.show', [0, 'filename' => 'emails.penyesuaian_upah0']) }}" class="module-card" data-module="notification" style="--i:13">
          <span class="m-head"><span class="m-name">PEMBERITAHUAN</span></span>
          <span class="m-corner-badge {{ count($notif_letter) > 0 ? '' : 'has-zero' }}">{{ count($notif_letter) }}</span>
          <svg class="m-art-svg" viewBox="0 0 52 52" fill="none" stroke="#ff5400" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:48px;height:48px;">
            <path d="M8 12h36a4 4 0 0 1 4 4v22a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V16a4 4 0 0 1 4-4z" fill="#fff7ed" stroke="#ff5400"/>
            <polyline points="48,16 26,30 4,16" stroke="#ff5400"/>
            <path d="M4 36l16-12" stroke="#fed7aa"/>
            <path d="M48 36l-16-12" stroke="#fed7aa"/>
          </svg>
        </a>
        @endcan

      </div>
    </div>
  </section>

</div>

<!-- Hidden Elements for Cuba clock.js and default.js compatibility -->
<div class="d-none" aria-hidden="true">
  <div class="clockbox">
    <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
      <g id="face"><circle class="circle" cx="300" cy="300" r="253.9"></circle><circle class="mid-circle" cx="300" cy="300" r="16.2"></circle></g>
      <g id="hour"><path class="hour-hand" d="M300.5 298V142"></path></g>
      <g id="minute"><path class="minute-hand" d="M300.5 298V67"></path></g>
      <g id="second"><path class="second-hand" d="M300.5 350V55"></path></g>
    </svg>
  </div>
  <div id="txt"></div>
</div>

<!-- Modal PDP -->
<div class="modal fade bd-example-modal-lg" id="autoShowModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 18px;">
            <div class="modal-header bg-light py-3 px-4" style="border-bottom: 1px solid #e2e8f0;">
                <h5 class="modal-title w-100 text-center fw-bold text-dark" id="myLargeModalLabel" style="font-size: 16px; letter-spacing: -0.3px;">SURAT PERSETUJUAN PEMROSESAN DATA PRIBADI</h5>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12">
                        <div style="text-align: justify; font-size: 14px; line-height: 1.6; color: #334155;">
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Saya memberikan persetujuan kepada PT Alita Praya Mitra (“<b>Alita</b>”) untuk melakukan pemrosesan Data Pribadi yang melekat pada identitas diri (“<b>Data Pribadi</b>”) yang telah saya berikan dan meratifikasi seluruh pemrosesan Data Pribadi yang telah dilakukan sebelum tanggal persetujuan ini.</li>
                                <li class="mb-2">Bahwa Alita akan menggunakan Data Pribadi tersebut untuk kebutuhan penyimpanan sebagai <i>database</i>, pelaporan kepada pihak ketiga yang diwajibkan oleh Peraturan Perundang-undangan di Indonesia dan/atau kegiatan-kegiatan lain yang berkaitan dengan kepentingan bisnis Alita.</li>
                                <li class="mb-2">Alita akan menyimpan Data Pribadi tersebut di sistem MATTA dan sistem database yang lain milik Alita dan Alita menjamin tidak ada penyalahgunaan atau penggunaan di luar kebutuhan yang tercantum dalam Ayat 2 di atas.</li>
                                <li class="mb-2">Saya telah mengetahui dan/atau menyetujui bahwa terdapat <i>Closed-Circuit Television (CCTV)</i> yang terpasang di lingkungan kerja Alita untuk kepentingan Perlindungan Data Pribadi Alita.</li>
                                <li class="mb-2">Saya memahami dan setuju bahwa Alita dapat mengungkapkan Data Pribadi tersebut kepada pihak ketiga lainnya dengan tujuan dan tata cara yang telah diatur dalam Peraturan Perundang-undangan yang berlaku, dengan pemberitahuan terlebih dahulu oleh Alita.</li>
                                <li class="mb-2">Alita akan melakukan penyimpanan Data Pribadi saya selama 10 (sepuluh) tahun sejak saya menyerahkan Data Pribadi saya.</li>
                                <li class="mb-2">Alita menjamin hak saya sebagai subjek Data Pribadi sesuai Undang-Undang Perlindungan Data Pribadi No. 27/2022, termasuk dengan Peraturan Perundang-undangan turunannya.</li>
                                <li class="mb-0">Saya menyatakan bahwa Data Pribadi dan dokumen yang saya berikan adalah benar dan sesuai dengan kondisi terkini.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-3 px-4 bg-light" style="border-top: 1px solid #e2e8f0;">
                <form action="{{ route('pdp.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @csrf
                    <input type="hidden" id="is_setuju" name="is_setuju" value="1">
                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                    <button class="btn btn-primary px-4 py-2" type="submit">Setuju</button>
                </form>
            </div>
        </div>
    </div>
</div>
<button id="autoClickBtn" type="button" class="btn btn-warning d-none" data-bs-toggle="modal" data-bs-target="#autoShowModal"></button>

<!-- Modal Survey PORA -->
<div class="modal fade bd-example-modal-lg" id="autoShowSurvey" tabindex="-1" role="dialog" aria-labelledby="labelSurvey" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 18px;">
            <div class="modal-header bg-light py-3 px-4" style="border-bottom: 1px solid #e2e8f0;">
                <h5 class="modal-title w-100 text-center fw-bold text-dark" id="labelSurvey" style="font-size: 16px; letter-spacing: -0.3px;">SURVEY CABANG OLAHRAGA PORA 2026</h5>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div style="text-align: justify; font-size: 14px; line-height: 1.6; color: #334155;">
                            <p class="mb-2">Dear Insan ALITA,<br/><b>Semangat Pagi!!!</b></p>
                            <p class="mb-0">Menjelang gelaran acara Pekan Olahraga Alita (PORA) 2026, mohon partisipasi dari seluruh Insan ALITA untuk mengikuti survey pemilihan 4 (empat) cabang olahraga yang akan diadakan dalam PORA 2026. Survey ini akan dibuka dari <b>Senin 02 Februari s/d Kamis 05 Februari 2026</b>.<br/>Terimakasih atas partisipasinya.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <form id="survey_pora" action="{{ route('survey_pora') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="5%" class="text-center align-middle fw-bold">1</td>
                                        <td>
                                            <div class="fw-bold mb-2 text-dark">Cabor GROUP (Pilih 2)</div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="futsal" id="opsi1_futsal" name="opsi1[]">
                                                <label class="form-check-label" for="opsi1_futsal">Futsal (5 vs 5)</label>
                                            </div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="voli" id="opsi1_voli" name="opsi1[]">
                                                <label class="form-check-label" for="opsi1_voli">Voli (6 vs 6)</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="basket" id="opsi1_basket" name="opsi1[]">
                                                <label class="form-check-label" for="opsi1_basket">Basket (3 on 3)</label>
                                            </div>
                                            <small id="error_opsi1" class="text-danger fw-bold d-block mt-1" style="display:none;">Wajib Pilih 2</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle fw-bold">2</td>
                                        <td>
                                            <div class="fw-bold mb-2 text-dark">Cabor INDIVIDU / DUO (Pilih 1)</div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="badminton" id="opsi2_badminton" name="opsi2[]">
                                                <label class="form-check-label" for="opsi2_badminton">Badminton (Ganda Putra & Ganda Putri)</label>
                                            </div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="tenis" id="opsi2_tenis" name="opsi2[]">
                                                <label class="form-check-label" for="opsi2_tenis">Tenis Meja (Ganda Putra)</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="renang" id="opsi2_renang" name="opsi2[]">
                                                <label class="form-check-label" for="opsi2_renang">Renang (Individu Putra & Putri)</label>
                                            </div>
                                            <small id="error_opsi2" class="text-danger fw-bold d-block mt-1" style="display:none;">Wajib Pilih 1</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle fw-bold">3</td>
                                        <td>
                                            <div class="fw-bold mb-2 text-dark">Cabor E-SPORT (Pilih 1)</div>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="ml" id="opsi3_ml" name="opsi3[]">
                                                <label class="form-check-label" for="opsi3_ml">Mobile Legend (5 vs 5)</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="fifa" id="opsi3_fifa" name="opsi3[]">
                                                <label class="form-check-label" for="opsi3_fifa">FIFA 26 (2 vs 2)</label>
                                            </div>
                                            <small id="error_opsi3" class="text-danger fw-bold d-block mt-1" style="display:none;">Wajib Pilih 1</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <div class="text-end mt-3">
                                <button class="btn btn-primary px-4 py-2" type="submit">Submit Survey</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<button id="btnShowSurvey" type="button" class="btn btn-warning d-none" data-bs-toggle="modal" data-bs-target="#autoShowSurvey"></button>

<script type="text/javascript">
// Live Clock
function updateDashLiveClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const timeStr = h + ':' + m;
    const el1 = document.getElementById('dash-live-time');
    const el2 = document.getElementById('dash-sub-time');
    const elTxt = document.getElementById('txt');
    if (el1) el1.textContent = timeStr;
    if (el2) el2.textContent = timeStr;
    if (elTxt) elTxt.textContent = timeStr;
}
setInterval(updateDashLiveClock, 1000);
updateDashLiveClock();

// Interactive Auto-Playing Info Carousel (Exact Match with MATTA User Home)
(function() {
    const track = document.getElementById('info-carousel-track');
    const container = document.querySelector('.info-container');
    const dots = document.querySelectorAll('#info-dots-wrap [data-dot]');
    const prevBtn = document.getElementById('info-prev-btn');
    const nextBtn = document.getElementById('info-next-btn');

    if (!track) return;

    let curSlide = 0;
    const total = track.children.length || 3;
    let autoTimer = null;
    let isSliding = false;
    const AUTO_DELAY = 4500; // 4.5 detik transisi otomatis yang halus

    function showSlide(idx) {
        if (isSliding) return;
        isSliding = true;
        curSlide = (idx + total) % total;
        track.style.transform = 'translate3d(-' + (curSlide * 100) + '%, 0, 0)';
        dots.forEach((d, i) => d.classList.toggle('active', i === curSlide));
        setTimeout(() => { isSliding = false; }, 650);
    }

    function nextSlide() {
        showSlide(curSlide + 1);
    }

    function prevSlide() {
        showSlide(curSlide - 1);
    }

    function startAuto() {
        stopAuto();
        autoTimer = setInterval(nextSlide, AUTO_DELAY);
    }

    function stopAuto() {
        if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
        }
    }

    function restartAuto() {
        stopAuto();
        startAuto();
    }

    // Tombol Navigasi
    prevBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        prevSlide();
        restartAuto();
    });

    nextBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        nextSlide();
        restartAuto();
    });

    // Titik Indikator Dots
    dots.forEach((dot, idx) => {
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            showSlide(idx);
            restartAuto();
        });
    });

    // Pause saat mouse diarahkan ke area carousel (hover)
    if (container) {
        container.addEventListener('mouseenter', stopAuto);
        container.addEventListener('mouseleave', startAuto);
    }

    // Pause saat tab browser tidak aktif
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAuto();
        } else {
            startAuto();
        }
    });

    // Pause saat modal detail sedang terbuka
    document.addEventListener('show.bs.modal', stopAuto);
    document.addEventListener('hidden.bs.modal', startAuto);

    // Dukungan Touch / Swipe untuk tablet & ponsel
    let touchStartX = 0;
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        stopAuto();
    }, { passive: true });

    track.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
            if (diff > 0) nextSlide();
            else prevSlide();
        }
        startAuto();
    }, { passive: true });

    // Mulai animasi otomatis
    startAuto();

    // Ekspos kontrol auto-slide ke window untuk modal pop-up
    window.stopCarouselAuto = stopAuto;
    window.startCarouselAuto = startAuto;
})();

// MATTA User Modal Pop-up Handlers
function openMattaModal(id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.classList.add('open');
    m.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    if (typeof window.stopCarouselAuto === 'function') {
        window.stopCarouselAuto();
    }
}

function closeMattaModal(id) {
    let m = (typeof id === 'string') ? document.getElementById(id) : id;
    if (!m) return;
    m.classList.remove('open');
    m.setAttribute('aria-hidden', 'true');
    if (!document.querySelector('.matta-pop-backdrop.open')) {
        document.body.style.overflow = '';
    }
    if (typeof window.startCarouselAuto === 'function') {
        window.startCarouselAuto();
    }
}

// Global backdrop click & ESC key handler for popups
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList && e.target.classList.contains('matta-pop-backdrop')) {
        closeMattaModal(e.target);
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const opened = document.querySelectorAll('.matta-pop-backdrop.open');
        opened.forEach(m => closeMattaModal(m));
    }
});

// Interactive Calendar (Identical to User Dashboard)
(function() {
    const grid = document.getElementById('admin-cal-grid');
    const title = document.getElementById('admin-cal-title');
    const realToday = new Date();
    let view = new Date(realToday.getFullYear(), realToday.getMonth(), 1);

    const MONTHS = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function renderCal() {
        const y = view.getFullYear(), m = view.getMonth();
        if (title) title.textContent = MONTHS[m] + " " + y;

        const first = new Date(y, m, 1).getDay();
        const days = new Date(y, m + 1, 0).getDate();
        const prevDays = new Date(y, m, 0).getDate();
        const cells = [];

        for (let i = first - 1; i >= 0; i--) cells.push({ d: prevDays - i, other: true });
        for (let d = 1; d <= days; d++) cells.push({ d: d });
        while (cells.length % 7) cells.push({ d: cells.length - days - first + 1, other: true });

        if (!grid) return;
        const isCurrentMonth = (y === realToday.getFullYear() && m === realToday.getMonth());

        grid.innerHTML = cells.map((c, i) => {
            const dow = i % 7;
            if (c.other) {
                const isSun = (dow === 0);
                const isSat = (dow === 6);
                return '<div class="cal-day-cell other' + (isSun ? ' is-sunday' : (isSat ? ' is-saturday' : '')) + '"><span class="cal-num">' + c.d + '</span></div>';
            }
            const isSunday = (dow === 0);
            const isSaturday = (dow === 6);
            const isToday = isCurrentMonth && (c.d === realToday.getDate());
            let cls = 'cal-day-cell';
            if (isSunday) cls += ' is-sunday';
            if (isSaturday) cls += ' is-saturday';
            if (isToday) cls += ' today';

            return '<div class="' + cls + '">' +
                (isToday ? '<span class="cal-today-box">' + c.d + '</span>' : '<span class="cal-num">' + c.d + '</span>') +
                '</div>';
        }).join('');
    }

    document.getElementById('btn-admin-cal-prev')?.addEventListener('click', () => {
        view.setMonth(view.getMonth() - 1);
        renderCal();
    });
    document.getElementById('btn-admin-cal-next')?.addEventListener('click', () => {
        view.setMonth(view.getMonth() + 1);
        renderCal();
    });

    renderCal();
})();

// Survey Validation
document.getElementById('survey_pora')?.addEventListener('submit', function(e){
    e.preventDefault();
    const checked_opsi1 = document.querySelectorAll('input[name="opsi1[]"]:checked');
    const checked_opsi2 = document.querySelectorAll('input[name="opsi2[]"]:checked');
    const checked_opsi3 = document.querySelectorAll('input[name="opsi3[]"]:checked');
    let valid = true;
    if(checked_opsi1.length !== 2){
        document.getElementById('error_opsi1').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('error_opsi1').style.display = 'none';
    }
    if(checked_opsi2.length !== 1){
        document.getElementById('error_opsi2').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('error_opsi2').style.display = 'none';
    }
    if(checked_opsi3.length !== 1){
        document.getElementById('error_opsi3').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('error_opsi3').style.display = 'none';
    }
    if(valid){
        e.target.submit();
    }
});

@if(!$is_survey_pora && now()->lessThanOrEqualTo(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2026-02-05 19:00:00')))
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnShowSurvey').click();
});
@endif
@if(!$is_pdp['is_pdp'] && $is_pdp['is_aktif'])
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('autoClickBtn').click();
});
@endif
var session_layout = '{{ session()->get('layout ') }}';
</script>
@endsection

@section('script')
<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
@endsection
