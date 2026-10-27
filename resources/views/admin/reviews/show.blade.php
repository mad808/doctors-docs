@extends('admin.layouts.app')

@section('title', __('Review Details'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Review Details') }} #{{ $review->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">{{ __('Reviews') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Details') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">{{ __('Comment') }}</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-warning text-dark fs-6 px-3 py-1">
                        <i class="bi bi-star-fill text-dark me-1"></i>{{ $review->rating }} / 5
                    </span>
                    <span class="text-muted small">{{ $review->created_at->format('d.m.Y H:i') }}</span>
                </div>

                <div class="p-3 bg-light rounded border mb-4">
                    {{ $review->comment }}
                </div>

                <hr>

                <h6 class="fw-bold mb-3">{{ __('Admin Reply') }}</h6>
                <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="admin_reply" rows="4" class="form-control" placeholder="{{ __('Write your reply...') }}" required>{{ old('admin_reply', $review->admin_reply) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-send me-1"></i>{{ __('Send Reply') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">{{ __('Patient') }}</h6>
            </div>
            <div class="card-body">
                <div class="fw-bold">{{ $review->user?->name ?? '—' }}</div>
                <div class="text-muted small mb-2">{{ $review->user?->phone ?? '—' }}</div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">{{ __('Target') }}</h6>
            </div>
            <div class="card-body">
                @if($review->doctorProfile)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge fs-4 text-primary"></i>
                        <div>
                            <div class="fw-bold">{{ $review->doctorProfile->user?->name ?? 'Doctor' }}</div>
                            <div class="text-muted small">
                                {{ $review->doctorProfile->{'title_' . app()->getLocale()} ?? $review->doctorProfile->title_tk }}
                            </div>
                        </div>
                    </div>
                @elseif($review->clinic)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-hospital fs-4 text-success"></i>
                        <div>
                            <div class="fw-bold">
                                {{ $review->clinic->{'name_' . app()->getLocale()} ?? $review->clinic->name_tk }}
                            </div>
                            <div class="text-muted small">
                                {{ $review->clinic->{'address_' . app()->getLocale()} ?? $review->clinic->address_tk }}
                            </div>
                        </div>
                    </div>
                @else
                    <span class="text-muted">—</span>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">{{ __('Status') }}</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @if($review->status === 1)
                        <span class="badge bg-success fs-6">{{ __('Approved') }}</span>
                    @elseif($review->status === 2)
                        <span class="badge bg-danger fs-6">{{ __('Rejected') }}</span>
                    @else
                        <span class="badge bg-warning text-dark fs-6">{{ __('Pending Moderation') }}</span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="1">
                        <button type="submit" class="btn btn-success btn-sm w-100" {{ $review->status === 1 ? 'disabled' : '' }}>
                            <i class="bi bi-check-lg me-1"></i>{{ __('Approve') }}
                        </button>
                    </form>

                    <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="2">
                        <button type="submit" class="btn btn-warning btn-sm w-100" {{ $review->status === 2 ? 'disabled' : '' }}>
                            <i class="bi bi-x-lg me-1"></i>{{ __('Reject') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection