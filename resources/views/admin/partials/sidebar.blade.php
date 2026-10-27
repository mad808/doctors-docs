<aside class="admin-sidebar d-flex flex-column" id="adminSidebar">
    @php
        $siteTitle = $site_settings?->{'site_name_' . app()->getLocale()} ?? $site_settings?->site_name_tk ?? 'ProDoctors';
    @endphp

    <div class="p-3 border-bottom border-secondary border-opacity-25">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none fw-bold fs-5 d-flex align-items-center gap-2">
            @if($site_settings?->logo)
                <img src="{{ asset('storage/' . $site_settings->logo) }}" height="32" class="object-fit-contain bg-white rounded p-1" alt="{{ $siteTitle }}">
            @else
                <i class="bi bi-heart-pulse-fill text-info"></i>
            @endif
            <span class="text-truncate">{{ $siteTitle }}</span>
        </a>
    </div>

    <div class="py-2 flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>{{ __('Appointments') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.doctors.index') }}" class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span>{{ __('Doctors') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.clinics.index') }}" class="nav-link {{ request()->routeIs('admin.clinics.*') ? 'active' : '' }}">
                    <i class="bi bi-hospital"></i>
                    <span>{{ __('Clinics') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.specialties.index') }}" class="nav-link {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>{{ __('Specialties') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>{{ __('Locations') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.patients.index') }}" class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>{{ __('Patients') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star-half"></i>
                    <span>{{ __('Reviews') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>
                    <span>{{ __('Banners') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>{{ __('Settings') }}</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-3 border-top border-secondary border-opacity-25 small">
        <a href="{{ route('home') }}" class="text-white text-decoration-none d-flex align-items-center gap-2" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>{{ __('View Website') }}</span>
        </a>
    </div>
</aside>