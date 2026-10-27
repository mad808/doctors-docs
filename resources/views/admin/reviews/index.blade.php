@extends('admin.layouts.app')

@section('title', __('Reviews'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Reviews') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Reviews') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 align-items-center">
            <div class="col-sm-4 col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- {{ __('All Reviews') }} --</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('Pending Moderation') }}</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                </select>
            </div>
            @if(request()->has('status') && request('status') !== '')
                <div class="col-auto">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Reset') }}</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th scope="col" style="width: 70px;">ID</th>
                    <th scope="col">{{ __('Patient') }}</th>
                    <th scope="col">{{ __('Target') }}</th>
                    <th scope="col">{{ __('Rating') }}</th>
                    <th scope="col" style="max-width: 300px;">{{ __('Comment') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td class="fw-bold">#{{ $review->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $review->user?->name ?? '—' }}</div>
                            <div class="text-muted small">{{ $review->user?->phone ?? '—' }}</div>
                        </td>
                        <td>
                            @if($review->doctorProfile)
                                <div class="badge bg-light text-primary border">
                                    <i class="bi bi-person-badge me-1"></i>{{ $review->doctorProfile->user?->name ?? 'Doctor' }}
                                </div>
                            @elseif($review->clinic)
                                <div class="badge bg-light text-success border">
                                    <i class="bi bi-hospital me-1"></i>{{ $review->clinic->{'name_' . app()->getLocale()} ?? $review->clinic->name_tk }}
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                <i class="bi bi-star-fill text-warning me-1"></i>{{ $review->rating }} / 5
                            </span>
                        </td>
                        <td style="max-width: 300px;">
                            <div class="text-truncate small" title="{{ $review->comment }}">
                                {{ $review->comment }}
                            </div>
                            @if($review->admin_reply)
                                <div class="small text-info mt-1">
                                    <i class="bi bi-reply-fill me-1"></i>{{ __('Admin Reply') }}: {{ Str::limit($review->admin_reply, 35) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($review->status === 1)
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @elseif($review->status === 2)
                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ __('Pending Moderation') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1 align-items-center">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-outline-info" title="{{ __('Review Details') }}">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if($review->status !== 1)
                                    <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('Approve') }}">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($review->status !== 2)
                                    <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ __('Reject') }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this review?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">{{ __('No reviews found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection