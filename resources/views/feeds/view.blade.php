@extends('layouts.simple.master')
@section('title', 'Feeds - MATTA Workplace')

@section('css')
@endsection

@section('style')
<style>
  .feeds-container {
    padding: 0 4px 30px;
  }
  .modal-feed-img {
    width: 100%;
    max-height: 380px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 20px;
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
  .btn-manage-feed {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
  }

  /* Feed Search Filter Toolbar */
  .feed-search-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    padding: 10px 18px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    flex-wrap: wrap;
  }
  .feed-search-wrap {
    position: relative;
    flex: 1;
    max-width: 520px;
  }
  @media (max-width: 768px) {
    .feed-search-wrap {
      max-width: 100%;
      width: 100%;
    }
  }
  .feed-search-wrap input {
    width: 100%;
    padding: 9px 36px 9px 38px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 9999px;
    border: 1px solid #e2e8f0;
    outline: none;
    background: #f8fafc;
    color: #1e293b;
    transition: all 0.18s ease;
  }
  .feed-search-wrap input:focus {
    background: #ffffff;
    border-color: #ff5400;
    box-shadow: 0 0 0 3px rgba(255, 84, 0, 0.12);
  }
  .feed-search-wrap .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
  }
  .feed-search-wrap .clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    background: none;
    border: none;
    cursor: pointer;
    display: none;
    padding: 2px;
    line-height: 1;
  }
  .feed-search-wrap .clear-btn:hover {
    color: #475569;
  }
  .feed-search-stats {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
  }
</style>
@endsection

@section('breadcrumb-title')
<h3>Feeds</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Applications</li>
<li class="breadcrumb-item active">Feeds</li>
@endsection

@section('content')
@php
    $activeFeeds = $feed->filter(fn($item) => $item->is_active == 1);
    $inactiveFeeds = $feed->filter(fn($item) => $item->is_active == 0);
    $activeCount = $activeFeeds->count();
    $inactiveCount = $inactiveFeeds->count();

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
@endphp

<div class="container-fluid feeds-container">

    <!-- FEEDS HEAD / HEADER -->
    <div class="feeds-head">
        <div>
            <h1 class="page-title" id="feeds-main-title">Feeds Aktif</h1>
            <p class="page-sub" id="feeds-main-sub">Pengumuman yang sedang berlaku dan tampil untuk seluruh jajaran insan ALITA.</p>
        </div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="tabs-pill" role="tablist" aria-label="Status feed">
                <button type="button" role="tab" id="tab-btn-active" aria-selected="true" onclick="switchFeedTab('active')">
                    <span class="dot-live" aria-hidden="true"></span>
                    <span>Feed Aktif</span>
                    <span class="tab-count">{{ $activeCount }}</span>
                </button>
                <button type="button" role="tab" id="tab-btn-inactive" aria-selected="false" onclick="switchFeedTab('inactive')">
                    <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect width="20" height="5" x="2" y="3" rx="1"/>
                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/>
                        <path d="M10 12h4"/>
                    </svg>
                    <span>Feed Non Aktif</span>
                    <span class="tab-count">{{ $inactiveCount }}</span>
                </button>
            </div>
            @can('hc')
            <a href="{{ route('feeds.index') }}" class="btn btn-outline-primary btn-manage-feed" title="Kelola data master feed">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <span>Kelola Feeds</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- FEED SEARCH TOOLBAR (FILTER SEARCH ONLY) -->
    <div class="feed-search-toolbar" id="feed-search-toolbar">
        <div class="feed-search-wrap">
            <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="feed-search-input" placeholder="Cari judul, topik, atau isi pengumuman..." onkeyup="filterFeedSearch(this.value)" autocomplete="off">
            <button type="button" id="feed-search-clear" class="clear-btn" onclick="clearFeedSearch()" title="Hapus pencarian">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="feed-search-stats">
            <span id="feed-status-counter">{{ $activeCount }} feed aktif</span>
            <span id="feed-search-match" style="display: none; color: #ff5400;">&bull; <span id="feed-match-number">0</span> ditemukan</span>
        </div>
    </div>

    <!-- ARCHIVE NOTE -->
    <div class="archive-note" id="archive-note-alert" style="display: none;">
        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>Feed non aktif sudah tidak berlaku dan tidak lagi tampil di beranda karyawan. Data ditampilkan sebagai arsip perusahaan.</span>
    </div>

    <!-- FEED GRID -->
    <div class="feed-grid" id="feed-grid-container">
        @php $cardIdx = 0; @endphp
        @forelse($feed as $data)
            @php
                $isActive = ($data->is_active == 1);
                $feedImg = resolveFeedImage($data->title, $data->id, $availableImages);
                $feedTag = resolveFeedTag($data->title);
                $feedExcerpt = \Illuminate\Support\Str::limit(strip_tags($data->content), 125, '...');
            @endphp
            <article class="feed-card feed-card-item {{ $isActive ? 'status-active' : 'status-inactive is-inactive' }}" 
                     data-feed-status="{{ $isActive ? 'active' : 'inactive' }}" 
                     data-feed-category="{{ $feedTag }}"
                     data-feed-title="{{ strtolower($data->title) }}"
                     style="--i:{{ $cardIdx++ }}; display: {{ $isActive ? 'flex' : 'none' }};">
                <div class="feed-body">
                    <div class="feed-media" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#feedDetailModal{{ $data->id }}">
                        <img class="feed-img" src="{{ asset('matta-ui/img/feeds/' . $feedImg) }}" alt="{{ $data->title }}" loading="lazy">
                        @if($isActive)
                            <span class="feed-flag on"><span class="dot-live"></span>Aktif</span>
                        @else
                            <span class="feed-flag off">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="5" x="2" y="3" rx="1"/>
                                    <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/>
                                    <path d="M10 12h4"/>
                                </svg>
                                Non Aktif
                            </span>
                        @endif
                    </div>
                    <span class="feed-tag">{{ $feedTag }}</span>
                    <h2 class="feed-title">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#feedDetailModal{{ $data->id }}">{{ $data->title }}</a>
                    </h2>
                    <p class="feed-excerpt">{{ $feedExcerpt }}</p>
                    <div class="feed-date">
                        @if($isActive)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2"/>
                                <path d="M16 2v4"/>
                                <path d="M8 2v4"/>
                                <path d="M3 10h18"/>
                            </svg>
                            <span>{{ $data->created_at }}</span>
                        @else
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="5" x="2" y="3" rx="1"/>
                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/>
                                <path d="M10 12h4"/>
                            </svg>
                            <span><span>Diarsipkan</span> &bull; {{ $data->created_at }}</span>
                        @endif
                    </div>
                </div>
                <div class="feed-foot">
                    <button type="button" class="link-more" data-bs-toggle="modal" data-bs-target="#feedDetailModal{{ $data->id }}">
                        <span>{{ $isActive ? 'Lihat detail' : 'Lihat arsip' }}</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </div>
            </article>

            <!-- MODAL DETAIL FEED -->
            <div class="modal fade" id="feedDetailModal{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="feedModalTitle{{ $data->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);">
                        <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="feed-tag m-0">{{ $feedTag }}</span>
                                @if($isActive)
                                    <span class="badge bg-success" style="font-size: 11px;">Aktif</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 11px;">Non Aktif</span>
                                @endif
                            </div>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="padding: 24px 28px;">
                            <img class="modal-feed-img" src="{{ asset('matta-ui/img/feeds/' . $feedImg) }}" alt="{{ $data->title }}">
                            <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.35; margin-bottom: 8px;" id="feedModalTitle{{ $data->id }}">
                                {{ $data->title }}
                            </h2>
                            <div class="d-flex align-items-center gap-3 text-muted mb-4" style="font-size: 12.5px;">
                                <div class="d-flex align-items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                                        <path d="M16 2v4"/>
                                        <path d="M8 2v4"/>
                                        <path d="M3 10h18"/>
                                    </svg>
                                    <span>{{ $data->created_at }}</span>
                                </div>
                                <div>&bull;</div>
                                <div class="d-flex align-items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <span>Dipublikasikan oleh {{ App\Http\Controllers\FeedController::getKaryawan($data->created_by) }}</span>
                                </div>
                            </div>
                            <hr style="border-top: 1px solid #f1f5f9; margin-bottom: 20px;">
                            <div class="feed-content-prose">
                                {!! $data->content !!}
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #fafbfc; border-top: 1px solid #f1f5f9; padding: 14px 24px; justify-content: space-between;">
                            <div>
                                @can('hc')
                                <a href="{{ route('feeds.edit', $data->id) }}" class="btn btn-outline-warning btn-sm" style="border-radius: 8px; font-weight: 600; font-size: 12.5px;">
                                    <i class="fa fa-pencil"></i> Edit Konten Feed
                                </a>
                                @endcan
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="window.print();" style="border-radius: 8px;">
                                    <i class="fa fa-print"></i> Cetak
                                </button>
                                <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal" style="border-radius: 8px;">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">Belum ada feed yang dipublikasikan.</div>
        @endforelse

        <div class="empty-state" id="empty-state-notice" style="display: none; grid-column: 1 / -1; padding: 40px 20px; text-align: center; color: #64748b; font-weight: 500;">
            Tidak ada feed dalam kategori ini.
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
let currentTab = 'active';
let searchQuery = '';

function applyFeedFilters() {
    const cards = document.querySelectorAll('.feed-card-item');
    const emptyNotice = document.getElementById('empty-state-notice');
    const matchWrap = document.getElementById('feed-search-match');
    const matchNum = document.getElementById('feed-match-number');
    let visibleCount = 0;

    cards.forEach(card => {
        const status = card.dataset.feedStatus;
        const title = (card.dataset.feedTitle || '').toLowerCase();
        const category = (card.dataset.feedCategory || '').toLowerCase();

        const matchesTab = (status === currentTab);
        const matchesSearch = (!searchQuery || title.includes(searchQuery) || category.includes(searchQuery));

        if (matchesTab && matchesSearch) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    if (matchWrap && matchNum) {
        if (searchQuery.length > 0) {
            matchWrap.style.display = 'inline-block';
            matchNum.textContent = visibleCount;
        } else {
            matchWrap.style.display = 'none';
        }
    }

    if (visibleCount === 0) {
        emptyNotice.style.display = 'block';
        if (searchQuery) {
            emptyNotice.textContent = 'Tidak ada feed yang cocok dengan pencarian "' + searchQuery + '".';
        } else {
            emptyNotice.textContent = 'Tidak ada feed dalam daftar ini.';
        }
    } else {
        emptyNotice.style.display = 'none';
    }
}

function filterFeedSearch(val) {
    searchQuery = (val || '').toLowerCase().trim();
    const clearBtn = document.getElementById('feed-search-clear');
    if (clearBtn) {
        clearBtn.style.display = searchQuery.length > 0 ? 'block' : 'none';
    }
    applyFeedFilters();
}

function clearFeedSearch() {
    const input = document.getElementById('feed-search-input');
    if (input) {
        input.value = '';
    }
    filterFeedSearch('');
}

function switchFeedTab(status) {
    currentTab = status;
    const activeBtn = document.getElementById('tab-btn-active');
    const inactiveBtn = document.getElementById('tab-btn-inactive');
    const title = document.getElementById('feeds-main-title');
    const sub = document.getElementById('feeds-main-sub');
    const archiveNote = document.getElementById('archive-note-alert');
    const statusCounter = document.getElementById('feed-status-counter');

    if (status === 'active') {
        activeBtn.setAttribute('aria-selected', 'true');
        inactiveBtn.setAttribute('aria-selected', 'false');
        title.textContent = 'Feeds Aktif';
        sub.textContent = 'Pengumuman yang sedang berlaku dan tampil untuk seluruh jajaran insan ALITA.';
        archiveNote.style.display = 'none';
        if (statusCounter) statusCounter.textContent = '{{ $activeCount }} feed aktif';
    } else {
        activeBtn.setAttribute('aria-selected', 'false');
        inactiveBtn.setAttribute('aria-selected', 'true');
        title.textContent = 'Feeds Non Aktif';
        sub.textContent = 'Arsip pengumuman masa lalu yang telah dinonaktifkan dari beranda pengguna.';
        archiveNote.style.display = 'flex';
        if (statusCounter) statusCounter.textContent = '{{ $inactiveCount }} arsip non aktif';
    }

    applyFeedFilters();
}
</script>
@endsection
