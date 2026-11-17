<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
            </div>
        </div>
        {{-- <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0 text-start"> --}}
        <div class="nav-right col-auto ms-auto p-0">
            <ul class="nav-menus">
                @can('hc')
                <li class="portal-switch-item py-0">
                    <a href="{{ route('matta.dashboard') }}" class="portal-switch-btn" title="Beralih ke Portal Karyawan (Self-Service)">
                        <svg class="icon" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Portal Karyawan</span>
                    </a>
                </li>
                @endcan
                <li class="lang-nav-item py-0">
                    @php
                        $currentLocale = strtolower(session('locale', App::getLocale() ?? 'en'));
                    @endphp
                    <div class="lang-pill" role="group" aria-label="Language">
                        <a href="{{ url('lang/en') }}" class="lang-btn {{ $currentLocale === 'en' ? 'active' : '' }}" aria-pressed="{{ $currentLocale === 'en' ? 'true' : 'false' }}">EN</a>
                        <a href="{{ url('lang/id') }}" class="lang-btn {{ $currentLocale === 'id' ? 'active' : '' }}" aria-pressed="{{ $currentLocale === 'id' ? 'true' : 'false' }}">ID</a>
                    </div>
                </li>
                <li class="profile-nav onhover-dropdown pe-0 py-0 position-relative">
                    <div class="profile-badge" role="button" tabindex="0" aria-label="User Profile Menu">
                        <div class="profile-info text-end">
                            <div class="profile-name">{{ auth()->user()->name }}</div>
                            <div class="profile-nip">{{ auth()->user()->nik }}</div>
                        </div>
                        <span class="avatar-btn">
                            <svg class="icon icon-md" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <li>
                            <a href="{{ route('karyawan.show', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik)) }}" class="profile-dropdown-item">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('attendance.index') }}" class="profile-dropdown-item">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <span>Attendance Log</span>
                            </a>
                        </li>
                        @can('hc')
                        <li>
                            <a href="{{ route('matta.dashboard') }}" class="profile-dropdown-item">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span>Portal Karyawan</span>
                            </a>
                        </li>
                        @endcan
                        <li class="profile-dropdown-divider-li">
                            <hr class="profile-dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logoutapp') }}" method="post" id="header-logout-form" style="display:none;">
                                @method('POST')
                                @csrf
                            </form>
                            <button type="button" class="profile-dropdown-item danger" onclick="document.getElementById('header-logout-form').submit();">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                <span>Log out</span>
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
