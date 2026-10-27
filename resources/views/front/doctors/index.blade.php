@extends('front.layouts.app')

@section('title', __('Find Verified Doctors') . ' - ProDoctors')

@push('styles')
<style>
    .doctor-card-modern {
        border-radius: 16px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        border: 1px solid rgba(226, 232, 240, 0.9);
        background: #ffffff;
    }
    .doctor-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        border-color: rgba(2, 132, 199, 0.35);
    }
    .avatar-wrapper {
        width: 76px;
        height: 76px;
    }
    .specialty-pill {
        background: #f1f5f9;
        color: #334155;
        font-weight: 500;
        font-size: 0.785rem;
        padding: 4px 10px;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
    }
    .clinic-badge {
        font-size: 0.8rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .fee-tag {
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--pd-primary);
        letter-spacing: -0.01em;
    }
    .filter-header-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
</style>
@endpush

@section('content')
<!-- Page Header & Filter Section -->
<div class="py-4 py-lg-5 bg-white border-bottom shadow-2xs">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active fw-semibold text-primary-custom">{{ __('Doctors') }}</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark mb-1">{{ __('Find Verified Doctors') }}</h2>
                <p class="text-muted small mb-0">{{ __('Browse top medical professionals, check real patient ratings, and book appointments instantly.') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-primary bg-opacity-10 text-primary-custom px-3 py-2 rounded-pill fw-semibold small">
                    <i class="bi bi-person-check-fill me-1"></i>
                    {{ __('Found') }}: {{ $doctors->total() }} {{ __('verified specialists') }}
                </span>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="filter-header-card p-3 p-lg-4">
            <form method="GET" action="{{ route('doctors.index') }}" class="row g-2 align-items-center">
                <div class="col-md-6 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0 py-2" placeholder="{{ __('Doctor name or keyword...') }}">
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <select name="specialty_id" class="form-select select2-basic">
                        <option value="">{{ __('All Specialties') }}</option>
                        @foreach($specialties as $sp)
                            <option value="{{ $sp->id }}" {{ request('specialty_id') == $sp->id ? 'selected' : '' }}>
                                {{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 col-lg-3">
                    <select name="location_id" class="form-select select2-basic">
                        <option value="">{{ __('All Cities') }}</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->{'name_' . app()->getLocale()} ?? $loc->name_tk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 col-lg-2">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('Newest') }}</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>{{ __('Highest Rating') }}</option>
                        <option value="experience" {{ request('sort') === 'experience' ? 'selected' : '' }}>{{ __('Most Experience') }}</option>
                    </select>
                </div>

                <div class="col-lg-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-2xs">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->anyFilled(['q', 'specialty_id', 'location_id', 'sort']))
                        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary py-2" title="{{ __('Reset Filters') }}">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Doctors Grid -->
<div class="container py-5">
    <div class="row g-4">
        @forelse($doctors as $doctor)
            <div class="col-md-6 col-lg-4">
                <div class="doctor-card-modern p-4 h-100 d-flex flex-column shadow-sm">
                    <!-- Top Doctor Info -->
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="avatar-wrapper position-relative flex-shrink-0">
                            @if($doctor->user?->avatar)
                                <img src="{{ asset('storage/' . $doctor->user->avatar) }}" class="rounded-circle border object-fit-cover w-100 h-100 shadow-2xs" alt="Doc">
                            @else
                                <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center w-100 h-100 fs-3 shadow-2xs">
                                    {{ strtoupper(substr($doctor->user?->name ?? 'D', 0, 1)) }}
                                </div>
                            @endif

                            @if($doctor->is_verified)
                                <i class="bi bi-patch-check-fill text-primary position-absolute bottom-0 end-0 bg-white rounded-circle fs-5 shadow-2xs" title="{{ __('Verified Doctor') }}"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="fw-bold mb-1 text-truncate">
                                <a href="{{ route('doctors.show', $doctor) }}" class="text-decoration-none text-dark hover-primary">
                                    {{ $doctor->user?->name }}
                                </a>
                            </h5>
                            <div class="text-muted small text-truncate mb-2">
                                {{ $doctor->{'title_' . app()->getLocale()} ?? $doctor->title_tk }}
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <span class="rating-badge small shadow-2xs py-1 px-2">
                                    <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($doctor->rating, 1) }}
                                </span>
                                <span class="text-muted very-small">({{ $doctor->reviews_count }} {{ __('reviews') }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Specialties Badges -->
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach($doctor->specialties->take(3) as $sp)
                            <span class="specialty-pill">{{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}</span>
                        @endforeach
                    </div>

                    <!-- Clinics & Experience Info -->
                    <div class="mb-3 d-flex flex-column gap-1 bg-light p-3 rounded-3 border">
                        <div class="d-flex align-items-center justify-content-between small text-muted mb-1">
                            <span class="fw-semibold text-secondary"><i class="bi bi-award me-1 text-primary"></i>{{ __('Experience') }}</span>
                            <span class="fw-bold text-dark">{{ $doctor->experience_years }} {{ __('years') }}</span>
                        </div>

                        @forelse($doctor->clinics->take(2) as $cl)
                            <div class="clinic-badge text-truncate">
                                <i class="bi bi-hospital text-primary-custom flex-shrink-0"></i>
                                <span class="text-truncate">{{ $cl->{'name_' . app()->getLocale()} ?? $cl->name_tk }}</span>
                                <span class="text-muted very-small">({{ $cl->location?->{'name_' . app()->getLocale()} ?? $cl->location?->name_tk }})</span>
                            </div>
                        @empty
                            <div class="text-muted very-small">—</div>
                        @endforelse
                    </div>

                    <!-- Fee & Action Button Footer -->
                    <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted very-small fw-semibold text-uppercase">{{ __('Consultation Fee') }}</div>
                            @if($doctor->consultation_fee)
                                <div class="fee-tag">{{ number_format($doctor->consultation_fee, 2) }} <span class="fs-6 fw-bold">TMT</span></div>
                            @else
                                <span class="text-muted small fw-semibold">{{ __('Free / Not specified') }}</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @if($doctor->user?->phone)
                                <a href="tel:{{ $doctor->user->phone }}" class="btn btn-outline-success btn-sm px-2 rounded-circle shadow-2xs" title="{{ __('Direct Call') }}">
                                    <i class="bi bi-telephone-fill"></i>
                                </a>
                            @endif

                            <button type="button" class="btn btn-sm btn-primary px-3 py-2 fw-semibold rounded-pill shadow-2xs" onclick="openBookingModal('{{ $doctor->id }}', '{{ addslashes($doctor->user?->name) }}')">
                                <i class="bi bi-calendar2-check me-1"></i>{{ __('Book an Appointment') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center p-4 mb-3">
                    <i class="bi bi-person-slash fs-1 text-muted"></i>
                </div>
                <h4 class="fw-bold text-dark">{{ __('No matching doctors found.') }}</h4>
                <p class="text-muted small mb-3">{{ __('Try adjusting your filters or search keywords to find doctors.') }}</p>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary btn-sm px-4 rounded-pill">
                    {{ __('Reset Filters') }}
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($doctors->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $doctors->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $('.select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    function openBookingModal(doctorId, doctorName) {
        $('#popupDoctorId').val(doctorId);
        $('#popupDocName').text(doctorName);
        const modal = new bootstrap.Modal(document.getElementById('quickBookingModal'));
        modal.show();
    }
</script>
@endpush