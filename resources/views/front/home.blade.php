@extends('front.layouts.app')

@section('title', __('Home') . ' - ProDoctors')

@push('styles')
<style>
    .hero-gradient {
        background: radial-gradient(circle at 10% 20%, rgba(2, 132, 199, 0.08) 0%, rgba(248, 250, 252, 0.5) 90%);
    }
    .feature-icon-box {
        width: 58px;
        height: 58px;
        transition: transform .25s ease, background-color .25s ease;
    }
    .card-feature:hover .feature-icon-box {
        transform: scale(1.1) rotate(4deg);
        background-color: var(--pd-primary) !important;
        color: #ffffff !important;
    }
    .specialty-tile {
        transition: all .2s ease-in-out;
    }
    .specialty-tile:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(2, 132, 199, 0.12);
        border-color: rgba(2, 132, 199, 0.3) !important;
    }
    .stat-number {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .badge-soft-primary {
        background: rgba(2, 132, 199, 0.1);
        color: var(--pd-primary);
    }
    .cta-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
</style>
@endpush

@section('content')
@include('front.partials.banner')

<!-- Hero & Search Section -->
<section class="py-5 hero-gradient border-bottom">
    <div class="container text-center">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill badge-soft-primary small fw-semibold mb-3">
            <i class="bi bi-shield-check text-primary-custom"></i>
            <span>{{ __('Verified Medical Network') }}</span>
        </div>

        <h1 class="display-5 fw-bold text-dark mb-3">
            {{ __('Find and Book the Best Doctors') }}
        </h1>
        <p class="text-secondary mx-auto mb-4 fs-5" style="max-width: 680px;">
            {{ __('Find trusted doctors, book verified appointments, and explore leading medical clinics across Turkmenistan.') }}
        </p>

        <!-- Search Bar -->
        <div class="card shadow-lg border-0 p-3 mx-auto rounded-4" style="max-width: 860px;">
            <form action="{{ route('doctors.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control bg-light border-0 py-2 ps-2" placeholder="{{ __('Search by doctor name...') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="specialty_id" class="form-select select2-basic">
                        <option value="">{{ __('All Specialties') }}</option>
                        @foreach($specialties as $sp)
                            <option value="{{ $sp->id }}">{{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm rounded-3">
                        <i class="bi bi-search me-1"></i>{{ __('Search') }}
                    </button>
                </div>
            </form>

            <div class="d-flex align-items-center flex-wrap gap-2 pt-2 ps-1 mt-2 border-top text-start">
                <span class="very-small text-muted fw-bold text-uppercase">{{ __('Quick Search') }}:</span>
                @foreach($specialties->take(4) as $quickSp)
                    <a href="{{ route('doctors.index', ['specialty_id' => $quickSp->id]) }}" class="badge bg-light text-secondary border text-decoration-none small py-1 px-2">
                        {{ $quickSp->{'name_' . app()->getLocale()} ?? $quickSp->name_tk }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Stats Metrics Counter -->
<section class="py-4 bg-white border-bottom shadow-2xs">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3 border-end border-light-subtle">
                <div class="stat-number text-primary-custom">2,500+</div>
                <div class="text-muted small fw-semibold">{{ __('Active Patients') }}</div>
            </div>
            <div class="col-6 col-md-3 border-end border-light-subtle">
                <div class="stat-number text-dark">150+</div>
                <div class="text-muted small fw-semibold">{{ __('Verified Doctors') }}</div>
            </div>
            <div class="col-6 col-md-3 border-end border-light-subtle">
                <div class="stat-number text-primary-custom">35+</div>
                <div class="text-muted small fw-semibold">{{ __('Top Clinics') }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number text-success">98%</div>
                <div class="text-muted small fw-semibold">{{ __('Patient Satisfaction') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Specialties Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">{{ __('Popular Specialties') }}</h3>
                <p class="text-muted small mb-0">{{ __('Explore specialists by their medical category') }}</p>
            </div>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold rounded-pill">
                {{ __('View All Doctors') }} <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($specialties as $sp)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('doctors.index', ['specialty_id' => $sp->id]) }}" class="card specialty-tile text-center text-decoration-none p-3 h-100 border bg-white rounded-3 shadow-2xs">
                        <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="{{ $sp->icon ?? 'bi bi-heart-pulse' }} fs-4"></i>
                        </div>
                        <div class="fw-bold text-dark small text-truncate">{{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}</div>
                        <div class="text-muted very-small mt-1">{{ $sp->doctors_count }} {{ __('Doctors') }}</div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-muted text-center py-4">{{ __('No specialties found.') }}</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Top Rated Doctors Section -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">{{ __('Top Rated Doctors') }}</h3>
                <p class="text-muted small mb-0">{{ __('Experienced and verified healthcare professionals') }}</p>
            </div>
            <a href="{{ route('doctors.index', ['sort' => 'rating']) }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold rounded-pill">
                {{ __('View All Doctors') }} <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($top_doctors as $doc)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 card-hover p-3 text-center border rounded-4 shadow-sm">
                        <div class="position-relative mx-auto mb-3" style="width: 86px; height: 86px;">
                            @if($doc->user?->avatar)
                                <img src="{{ asset('storage/' . $doc->user->avatar) }}" class="rounded-circle border object-fit-cover w-100 h-100 shadow-sm" alt="Doc">
                            @else
                                <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center w-100 h-100 fs-3 shadow-sm">
                                    {{ strtoupper(substr($doc->user?->name ?? 'D', 0, 1)) }}
                                </div>
                            @endif
                            @if($doc->is_verified)
                                <i class="bi bi-patch-check-fill text-primary position-absolute bottom-0 end-0 bg-white rounded-circle fs-5 shadow-2xs" title="{{ __('Verified Doctor') }}"></i>
                            @endif
                        </div>

                        <h6 class="fw-bold mb-1 text-truncate">
                            <a href="{{ route('doctors.show', $doc) }}" class="text-decoration-none text-dark hover-primary">{{ $doc->user?->name }}</a>
                        </h6>
                        <div class="text-muted small text-truncate mb-2">
                            {{ $doc->{'title_' . app()->getLocale()} ?? $doc->title_tk }}
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span class="rating-badge small shadow-2xs">
                                <i class="bi bi-star-fill text-warning"></i>{{ number_format($doc->rating, 1) }}
                            </span>
                            <span class="text-muted very-small">({{ $doc->reviews_count }} {{ __('reviews') }})</span>
                        </div>

                        <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between small text-muted">
                            <span class="fw-semibold">{{ $doc->experience_years }} {{ __('years') }}</span>
                            <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 rounded-pill" onclick="openBookingModal('{{ $doc->id }}', '{{ addslashes($doc->user?->name) }}')">
                                {{ __('Book an Appointment') }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted text-center py-4">{{ __('No doctors found.') }}</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Why Choose Us Features -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="fw-bold text-dark mb-1">{{ __('Why Choose ProDoctors?') }}</h3>
        <p class="text-muted small mb-5">{{ __('Simple, reliable and fast healthcare access in Turkmenistan') }}</p>

        <div class="row g-4 text-start">
            <div class="col-md-6 col-lg-3">
                <div class="card card-feature border-0 shadow-sm p-4 h-100 rounded-4 bg-white">
                    <div class="feature-icon-box rounded-3 bg-primary bg-opacity-10 text-primary-custom d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">{{ __('Verified Specialists') }}</h6>
                    <p class="text-muted small mb-0">{{ __('All doctors undergo credential verification and profile moderation.') }}</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-feature border-0 shadow-sm p-4 h-100 rounded-4 bg-white">
                    <div class="feature-icon-box rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-chat-left-dots fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">{{ __('Instant SMS Booking') }}</h6>
                    <p class="text-muted small mb-0">{{ __('Fast SMS OTP verification without lengthy sign-up forms.') }}</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-feature border-0 shadow-sm p-4 h-100 rounded-4 bg-white">
                    <div class="feature-icon-box rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-star-half fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">{{ __('Real Patient Reviews') }}</h6>
                    <p class="text-muted small mb-0">{{ __('Honest and transparent reviews checked by our moderators.') }}</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-feature border-0 shadow-sm p-4 h-100 rounded-4 bg-white">
                    <div class="feature-icon-box rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-calendar2-range fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">{{ __('24/7 Digital Booking') }}</h6>
                    <p class="text-muted small mb-0">{{ __('Book medical appointments anytime, anywhere from any device.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leading Clinics Section -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">{{ __('Leading Clinics') }}</h3>
                <p class="text-muted small mb-0">{{ __('Top hospitals, health complexes and diagnostic centers') }}</p>
            </div>
            <a href="{{ route('clinics.index') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold rounded-pill">
                {{ __('View All Clinics') }} <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($clinics as $clinic)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 card-hover border rounded-4 overflow-hidden shadow-2xs">
                        @if($clinic->image)
                            <img src="{{ asset('storage/' . $clinic->image) }}" class="card-img-top object-fit-cover" height="180" alt="{{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px;">
                                <i class="bi bi-hospital fs-1 text-secondary opacity-50"></i>
                            </div>
                        @endif

                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-light text-dark border small">
                                    <i class="bi bi-geo-alt text-primary-custom me-1"></i>{{ $clinic->location?->{'name_' . app()->getLocale()} ?? $clinic->location?->name_tk }}
                                </span>
                                <span class="rating-badge small">
                                    <i class="bi bi-star-fill text-warning"></i>{{ number_format($clinic->rating, 1) }}
                                </span>
                            </div>

                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('clinics.show', $clinic) }}" class="text-decoration-none text-dark hover-primary">
                                    {{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}
                                </a>
                            </h5>

                            <p class="text-muted small mb-0 text-truncate">
                                <i class="bi bi-pin-map me-1"></i>{{ $clinic->{'address_' . app()->getLocale()} ?? $clinic->address_tk }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted text-center py-4">{{ __('No clinics found.') }}</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Emergency / CTA Banner -->
<section class="py-5 cta-banner text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-2">{{ __('Need Urgent Medical Help?') }}</h2>
        <p class="text-light text-opacity-75 mb-4 mx-auto" style="max-width: 600px;">
            {{ __('Call directly to speak with our reception or book the earliest available slot.') }}
        </p>
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
            <a href="tel:+99362240774" class="btn btn-success btn-lg px-4 fw-semibold rounded-pill d-inline-flex align-items-center gap-2 shadow">
                <i class="bi bi-telephone-fill"></i>
                <span>+993 62 240774</span>
            </a>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-light btn-lg px-4 fw-semibold rounded-pill">
                {{ __('Explore Specialists') }}
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $('.select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    const swiper = new Swiper('.homeBannerSwiper', {
        loop: true,
        autoplay: {
            delay: 4500,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    function openBookingModal(doctorId, doctorName) {
        $('#popupDoctorId').val(doctorId);
        $('#popupDocName').text(doctorName);
        const modal = new bootstrap.Modal(document.getElementById('quickBookingModal'));
        modal.show();
    }
</script>
@endpush