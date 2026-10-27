<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2 shadow-sm">
    <div class="container">
        @php
            $siteTitle = $site_settings?->{'site_name_' . app()->getLocale()} ?? $site_settings?->site_name_tk ?? 'ProDoctors';
        @endphp

        <a class="navbar-brand fw-bold text-primary-custom d-flex align-items-center gap-2" href="{{ route('home') }}">
            @if($site_settings?->logo)
                <img src="{{ asset('storage/' . $site_settings->logo) }}" height="40" class="object-fit-contain" alt="{{ $siteTitle }}">
            @else
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary-custom p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-heart-pulse-fill fs-4"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-4 lh-1">{{ $siteTitle }}</span>
                    <span class="text-muted fw-normal" style="font-size: 0.65rem; letter-spacing: 0.05em;">LUKMANLAR PORTALY</span>
                </div>
            @endif
        </a>

        <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-1 py-1 px-2" type="button" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/img/flags/' . (app()->getLocale() === 'tk' ? 'tkm.png' : (app()->getLocale() === 'ru' ? 'rus.png' : 'eng.png'))) }}" width="18" height="12" alt="lang">
                    <span class="text-uppercase fw-bold small">{{ app()->getLocale() }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item small {{ app()->getLocale() === 'tk' ? 'active' : '' }}" href="{{ route('locale.switch', 'tk') }}">Türkmençe</a></li>
                    <li><a class="dropdown-item small {{ app()->getLocale() === 'ru' ? 'active' : '' }}" href="{{ route('locale.switch', 'ru') }}">Русский</a></li>
                    <li><a class="dropdown-item small {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('locale.switch', 'en') }}">English</a></li>
                </ul>
            </div>
        </div>

        <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-3 {{ request()->routeIs('home') ? 'active fw-bold text-primary-custom bg-primary bg-opacity-10' : 'text-secondary fw-semibold' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i>{{ __('Home') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-3 {{ request()->routeIs('doctors.*') ? 'active fw-bold text-primary-custom bg-primary bg-opacity-10' : 'text-secondary fw-semibold' }}" href="{{ route('doctors.index') }}">
                        <i class="bi bi-person-badge me-1"></i>{{ __('Doctors') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-3 {{ request()->routeIs('clinics.*') ? 'active fw-bold text-primary-custom bg-primary bg-opacity-10' : 'text-secondary fw-semibold' }}" href="{{ route('clinics.index') }}">
                        <i class="bi bi-hospital me-1"></i>{{ __('Clinics') }}
                    </a>
                </li>
            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 gap-lg-3 pt-3 pt-lg-0 border-top border-lg-0">
                <!-- <a href="tel:+99362240774" class="btn btn-light btn-sm text-secondary fw-semibold d-flex align-items-center gap-2 border px-3 py-1 shadow-2xs">
                    <i class="bi bi-telephone-fill text-success"></i>
                    <span>+993 62 240774</span>
                </a> -->

                <div class="dropdown d-none d-lg-block">
                    <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-2 py-1 px-2 fw-semibold" type="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/img/flags/' . (app()->getLocale() === 'tk' ? 'tkm.png' : (app()->getLocale() === 'ru' ? 'rus.png' : 'eng.png'))) }}" width="18" height="12" alt="lang">
                        <span class="text-uppercase small">{{ app()->getLocale() }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ app()->getLocale() === 'tk' ? 'active' : '' }}" href="{{ route('locale.switch', 'tk') }}">
                                <img src="{{ asset('assets/img/flags/tkm.png') }}" width="20" height="14" alt="tk">
                                <span class="small">Türkmençe</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ app()->getLocale() === 'ru' ? 'active' : '' }}" href="{{ route('locale.switch', 'ru') }}">
                                <img src="{{ asset('assets/img/flags/rus.png') }}" width="20" height="14" alt="ru">
                                <span class="small">Русский</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('locale.switch', 'en') }}">
                                <img src="{{ asset('assets/img/flags/eng.png') }}" width="20" height="14" alt="en">
                                <span class="small">English</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center gap-2 px-3 py-1 fw-semibold shadow-2xs" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>{{ __('Admin Panel') }}
                                    </a>
                                </li>
                            @elseif(auth()->user()->isDoctor())
                                <li>
                                    <a class="dropdown-item" href="{{ route('doctor.portal.dashboard') }}">
                                        <i class="bi bi-briefcase me-2"></i>{{ __('Doctor Portal') }}
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('patient.portal.dashboard') }}">
                                        <i class="bi bi-calendar-check me-2"></i>{{ __('My Appointments') }}
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3 py-1 fw-semibold shadow-2xs d-inline-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>{{ __('Login') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>