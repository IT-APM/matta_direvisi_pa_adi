@extends('matta.layouts.app', ['activeNav' => 'feeds'])

@section('title', 'Feeds')

@section('content')
<main class="main main-feeds" id="main" data-page="feeds">
  <div class="page-top">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('matta.dashboard') }}">Home</a>
      <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
      <span class="current" aria-current="page" data-crumb-current>Feeds</span>
    </nav>
  </div>

  <div class="feeds-head">
    <div>
      <h1 class="page-title" data-feeds-title>Feeds Aktif</h1>
      <p class="page-sub" data-feeds-sub>Pengumuman yang sedang berlaku dan tampil untuk seluruh karyawan.</p>
    </div>
    <div class="tabs-pill" role="tablist" aria-label="Status feed">
      <button type="button" role="tab" aria-selected="true" data-status="active">
        <span class="dot-live" aria-hidden="true"></span>Feed Aktif <span class="tab-count">{{ $activeCount }}</span>
      </button>
      <button type="button" role="tab" aria-selected="false" data-status="inactive">
        <svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>Feed Non Aktif <span class="tab-count">{{ $inactiveCount }}</span>
      </button>
    </div>
  </div>

  <div class="archive-note" data-archive-note hidden>
    <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
    <span>Feed non aktif sudah tidak berlaku dan tidak lagi tampil di beranda karyawan. Ditampilkan sebagai arsip.</span>
  </div>

  <div class="feed-grid">
    @php $idx = 0; @endphp
    @foreach($feeds as $f)
      @if(($f['set'] ?? '') === 'v2') @continue @endif
      @php
        $isActive = ($f['status'] === 'active');
        $detailUrl = route('matta.feed.detail', ['id' => $f['slug']]);
      @endphp
      <article class="feed-card {{ $isActive ? '' : 'is-inactive' }}" data-status="{{ $f['status'] }}" style="--i:{{ $idx++ }}">
        <div class="feed-body">
          <a class="feed-media" href="{{ $detailUrl }}" tabindex="-1" aria-hidden="true">
            <img class="feed-img" src="{{ asset('matta-ui/img/feeds/' . ($f['img'] ?: 'happy-new-year') . '.jpg') }}" alt="" loading="lazy">
            @if($isActive)
              <span class="feed-flag on"><span class="dot-live"></span>Aktif</span>
            @else
              <span class="feed-flag off"><svg class="icon icon-xs" viewBox="0 0 24 24" aria-hidden="true"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>Non Aktif</span>
            @endif
          </a>
          <span class="feed-tag">{{ $f['tag'] ?? 'INFORMASI' }}</span>
          <h2 class="feed-title"><a href="{{ $detailUrl }}">{{ $f['title'] }}</a></h2>
          <p class="feed-excerpt">{{ $f['excerpt'] }}</p>
          <div class="feed-date">
            @if($isActive)
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
              <span>{{ $f['stamp'] ?? $f['date'] }}</span>
            @else
              <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
              <span><span>Diarsipkan</span> · {{ $f['stamp'] ?? $f['date'] }}</span>
            @endif
          </div>
        </div>
        <div class="feed-foot">
          <a href="{{ $detailUrl }}" class="link-more">
            <span>{{ $isActive ? 'Lihat detail' : 'Lihat arsip' }}</span>
            <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
          </a>
        </div>
      </article>
    @endforeach
    <div class="empty-state" data-feed-empty hidden>Belum ada feed.</div>
  </div>
</main>
@endsection
