<header class="admin-header d-flex align-items-center justify-content-between px-3">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" onclick="$('#adminSidebar').toggleClass('show')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="fw-semibold text-secondary d-none d-sm-inline">ProDoctors Administration</span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/img/flags/' . (app()->getLocale() === 'tk' ? 'tkm.png' : (app()->getLocale() === 'ru' ? 'rus.png' : 'eng.png'))) }}" width="20" height="14" alt="flag">
                <span class="text-uppercase fw-semibold">{{ app()->getLocale() }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="{{ route('locale.switch', 'tk') }}">Türkmençe</a></li>
                <li><a class="dropdown-item" href="{{ route('locale.switch', 'ru') }}">Русский</a></li>
                <li><a class="dropdown-item" href="{{ route('locale.switch', 'en') }}">English</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2 border" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <span class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="bi bi-gear me-2"></i>{{ __('Settings') }}
                    </a>
                </li>
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
    </div>
</header>