@extends('front.layouts.app')

@section('title', __('Clinics') . ' - ProDoctors')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h3 class="fw-bold mb-3">{{ __('Clinics Directory') }}</h3>

        <form method="GET" action="{{ route('clinics.index') }}" class="row g-2">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="{{ __('Search clinic name...') }}">
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <select name="location_id" class="form-select select2-basic">
                    <option value="">{{ __('All Cities') }}</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->{'name_' . app()->getLocale()} ?? $loc->name_tk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3 fw-semibold">
                    {{ __('Search') }}
                </button>
                @if(request()->filled('q') || request()->filled('location_id'))
                    <a href="{{ route('clinics.index') }}" class="btn btn-outline-secondary">
                        {{ __('Clear') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        @forelse($clinics as $clinic)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 card-hover">
                    <div class="position-relative">
                        @if($clinic->image)
                            <img src="{{ asset('storage/' . $clinic->image) }}" class="card-img-top object-fit-cover" height="180" alt="{{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px;">
                                <i class="bi bi-hospital fs-1 text-secondary opacity-50"></i>
                            </div>
                        @endif

                        <div class="position-absolute bottom-0 start-0 m-3 d-flex align-items-center gap-2">
                            @if($clinic->logo)
                                <img src="{{ asset('storage/' . $clinic->logo) }}" class="rounded bg-white p-1 shadow-sm object-fit-cover" width="48" height="48" alt="Logo">
                            @endif
                        </div>

                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="rating-badge shadow-sm">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span>{{ number_format($clinic->rating, 1) }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                            <i class="bi bi-geo-alt text-primary-custom"></i>
                            <span>{{ $clinic->location?->{'name_' . app()->getLocale()} ?? $clinic->location?->name_tk ?? '—' }}</span>
                        </div>

                        <h5 class="fw-bold mb-2">
                            <a href="{{ route('clinics.show', $clinic) }}" class="text-decoration-none text-dark">
                                {{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}
                            </a>
                        </h5>

                        <p class="text-muted small mb-3 flex-grow-1">
                            {{ Str::limit($clinic->{'description_' . app()->getLocale()} ?? $clinic->description_tk, 90) }}
                        </p>

                        <div class="pt-2 border-top d-flex align-items-center justify-content-between">
                            <div class="text-muted small">
                                <i class="bi bi-telephone me-1"></i>{{ $clinic->phone_1 ?: '—' }}
                            </div>
                            <a href="{{ route('clinics.show', $clinic) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                {{ __('Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-hospital fs-1 text-muted"></i>
                <h5 class="text-muted mt-2">{{ __('No clinics found.') }}</h5>
            </div>
        @endforelse
    </div>

    @if($clinics->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $clinics->links() }}
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
</script>
@endpush