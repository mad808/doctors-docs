<footer class="bg-white border-top mt-auto pt-5 pb-3">
    <div class="container">
        @php
            $siteTitle = $site_settings?->{'site_name_' . app()->getLocale()} ?? $site_settings?->site_name_tk ?? 'ProDoctors';
        @endphp

        <div class="row g-4 pb-4">
            <div class="col-lg-4 col-md-6">
                <a class="text-decoration-none fw-bold text-primary-custom fs-4 d-flex align-items-center gap-2 mb-3" href="{{ route('home') }}">
                    @if($site_settings?->logo)
                        <img src="{{ asset('storage/' . $site_settings->logo) }}" height="40" alt="{{ $siteTitle }}">
                    @else
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary-custom p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="bi bi-heart-pulse-fill fs-5"></i>
                        </div>
                        <span class="lh-1">{{ $siteTitle }}</span>
                    @endif
                </a>

                <p class="text-muted small mb-3 pe-lg-3 lh-base">
                    {{ __('Find trusted doctors, book verified appointments, and explore leading medical clinics across Turkmenistan.') }}
                </p>

                <div class="d-flex align-items-center gap-2 pt-1">
                    <a href="https://github.com/mad808" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 px-3 py-1 shadow-sm" title="GitHub / Developer">
                        <i class="bi bi-github"></i>
                        <span class="small fw-semibold">mad808</span>
                    </a>

                    @if($site_settings?->instagram)
                        <a href="https://instagram.com/{{ ltrim($site_settings->instagram, '@') }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light border text-danger" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    @endif

                    @if($site_settings?->tiktok)
                        <a href="https://tiktok.com/@php echo '@' . ltrim($site_settings->tiktok, '@'); @endphp" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light border text-dark" title="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    @endif

                    @if($site_settings?->imo)
                        <a href="tel:{{ $site_settings->imo }}" class="btn btn-sm btn-light border text-primary" title="IMO">
                            <i class="bi bi-chat-dots-fill"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fw-bold mb-3 text-dark text-uppercase small" style="letter-spacing: 0.05em;">{{ __('Navigation') }}</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="text-decoration-none text-muted hover-primary">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('doctors.index') }}" class="text-decoration-none text-muted hover-primary">{{ __('Doctors') }}</a></li>
                    <li><a href="{{ route('clinics.index') }}" class="text-decoration-none text-muted hover-primary">{{ __('Clinics') }}</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="fw-bold mb-3 text-dark text-uppercase small" style="letter-spacing: 0.05em;">{{ __('Portals') }}</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                    <li><a href="{{ route('login') }}" class="text-decoration-none text-muted hover-primary">{{ __('Patient Login') }}</a></li>
                    <li><a href="{{ route('login') }}" class="text-decoration-none text-muted hover-primary">{{ __('Doctor Login') }}</a></li>
                    <li><a href="{{ route('login') }}" class="text-decoration-none text-muted hover-primary">{{ __('SMS Verification') }}</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-dark text-uppercase small" style="letter-spacing: 0.05em;">{{ __('Support & Info') }}</h6>
                <ul class="list-unstyled small text-muted mb-0 d-flex flex-column gap-2">
                    <li>
                        <a href="tel:+99362240774" class="text-decoration-none text-dark fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-telephone-fill text-success"></i>
                            <span>+993 62 240774</span>
                        </a>
                    </li>

                    @if($site_settings?->phone_1 && $site_settings->phone_1 !== '+99362240774')
                        <li>
                            <a href="tel:{{ $site_settings->phone_1 }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-2">
                                <i class="bi bi-telephone text-primary-custom"></i>
                                <span>{{ $site_settings->phone_1 }}</span>
                            </a>
                        </li>
                    @endif

                    @if($site_settings?->email)
                        <li>
                            <a href="mailto:{{ $site_settings->email }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-2">
                                <i class="bi bi-envelope text-primary-custom"></i>
                                <span>{{ $site_settings->email }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="d-flex align-items-center gap-2 pt-1">
                        <i class="bi bi-shield-check text-success fs-6"></i>
                        <span>{{ __('Verified Medical Network') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-top pt-3 d-flex flex-column flex-sm-row align-items-center justify-content-between small text-muted gap-2">
            <div>
                <span>&copy; {{ date('Y') }} {{ $siteTitle }}. {{ __('All rights reserved.') }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="https://github.com/mad808" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-muted d-flex align-items-center gap-1">
                    <i class="bi bi-code-slash text-primary-custom"></i>
                    <span>Developed by <strong>mad808</strong></span>
                </a>
                <span>•</span>
                <span>Turkmenistan</span>
            </div>
        </div>
    </div>
</footer>