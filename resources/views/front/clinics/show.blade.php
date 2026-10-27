@extends('front.layouts.app')

@section('title', ($clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk) . ' - ProDoctors')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clinics.index') }}">{{ __('Clinics') }}</a></li>
            <li class="breadcrumb-item active">{{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
        @if($clinic->image)
            <img src="{{ asset('storage/' . $clinic->image) }}" class="w-100 object-fit-cover" style="max-height: 280px;" alt="{{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}">
        @endif
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row align-items-start gap-3 justify-content-between">
                <div class="d-flex align-items-start gap-3">
                    @if($clinic->logo)
                        <img src="{{ asset('storage/' . $clinic->logo) }}" class="rounded border p-1 shadow-sm object-fit-cover" width="70" height="70" alt="Logo">
                    @endif
                    <div>
                        <h3 class="fw-bold mb-1">{{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}</h3>
                        <div class="text-muted small d-flex flex-wrap align-items-center gap-3">
                            <span><i class="bi bi-geo-alt text-primary-custom me-1"></i>{{ $clinic->location?->{'name_' . app()->getLocale()} ?? $clinic->location?->name_tk }}</span>
                            @if($clinic->{'address_' . app()->getLocale()} ?? $clinic->address_tk)
                                <span><i class="bi bi-pin-map me-1"></i>{{ $clinic->{'address_' . app()->getLocale()} ?? $clinic->address_tk }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="rating-badge fs-6 px-3 py-2 shadow-sm">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span>{{ number_format($clinic->rating, 1) }}</span>
                    </span>
                    <span class="text-muted small">({{ $clinic->reviews_count }} {{ __('reviews') }})</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">{{ __('About Clinic') }}</h5>
                    <div class="text-secondary leading-relaxed">
                        {{ $clinic->{'description_' . app()->getLocale()} ?? $clinic->description_tk ?? __('No description provided.') }}
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">{{ __('Doctors at this Clinic') }}</h5>
                    <div class="row g-3">
                        @forelse($clinic->doctors as $doctor)
                            <div class="col-md-6">
                                <div class="card h-100 border p-3 card-hover">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        @if($doctor->user?->avatar)
                                            <img src="{{ asset('storage/' . $doctor->user->avatar) }}" class="rounded-circle object-fit-cover border" width="50" height="50" alt="Doc">
                                        @else
                                            <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                {{ strtoupper(substr($doctor->user?->name ?? 'D', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="fw-bold mb-0">
                                                <a href="{{ route('doctors.show', $doctor) }}" class="text-decoration-none text-dark">
                                                    {{ $doctor->user?->name }}
                                                </a>
                                            </h6>
                                            <span class="text-muted small">{{ $doctor->{'title_' . app()->getLocale()} ?? $doctor->title_tk }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        @foreach($doctor->specialties as $sp)
                                            <span class="badge bg-light text-dark border small">{{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}</span>
                                        @endforeach
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top small">
                                        <span class="text-muted">{{ $doctor->experience_years }} {{ __('years') }} {{ __('Experience') }}</span>
                                        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary py-0 px-2 fw-semibold">{{ __('Book an Appointment') }}</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted text-center py-4">
                                {{ __('No doctors listed yet.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">{{ __('Contact') }}</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-3 text-secondary">
                        @if($clinic->phone_1)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone fs-5 text-primary-custom"></i>
                                <span class="fw-semibold text-dark">{{ $clinic->phone_1 }}</span>
                            </li>
                        @endif
                        @if($clinic->phone_2)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone fs-5 text-primary-custom"></i>
                                <span class="fw-semibold text-dark">{{ $clinic->phone_2 }}</span>
                            </li>
                        @endif
                        @if($clinic->website)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-globe fs-5 text-primary-custom"></i>
                                <a href="{{ $clinic->website }}" target="_blank" class="text-decoration-none text-truncate">{{ $clinic->website }}</a>
                            </li>
                        @endif
                        @if($clinic->{'address_' . app()->getLocale()} ?? $clinic->address_tk)
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt fs-5 text-primary-custom mt-n1"></i>
                                <span>{{ $clinic->{'address_' . app()->getLocale()} ?? $clinic->address_tk }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection