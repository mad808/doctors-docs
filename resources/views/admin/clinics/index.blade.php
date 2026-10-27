@extends('admin.layouts.app')

@section('title', __('Clinics'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Clinics') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Clinics') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.clinics.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>{{ __('Add Clinic') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th scope="col" style="width: 70px;">{{ __('Logo') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Location') }}</th>
                    <th scope="col">{{ __('Phone') }}</th>
                    <th scope="col">{{ __('Doctors') }}</th>
                    <th scope="col">{{ __('Rating') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clinics as $clinic)
                    <tr>
                        <td>
                            @if($clinic->logo)
                                <img src="{{ asset('storage/' . $clinic->logo) }}" class="rounded border object-fit-cover" width="48" height="48" alt="Logo">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center text-secondary" style="width: 48px; height: 48px;">
                                    <i class="bi bi-hospital fs-4"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}</div>
                            <div class="text-muted small">{{ $clinic->{'address_' . app()->getLocale()} ?? $clinic->address_tk ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-geo-alt me-1 text-primary"></i>{{ $clinic->location?->{'name_' . app()->getLocale()} ?? $clinic->location?->name_tk ?? '—' }}
                            </span>
                        </td>
                        <td class="small">
                            <div>{{ $clinic->phone_1 ?: '—' }}</div>
                            @if($clinic->phone_2)
                                <div class="text-muted">{{ $clinic->phone_2 }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                <i class="bi bi-people me-1"></i>{{ $clinic->doctors_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($clinic->rating, 1) }}
                            </span>
                        </td>
                        <td>
                            @if($clinic->status == 1)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.clinics.edit', $clinic) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.clinics.destroy', $clinic) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this clinic?') }}')">
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
                        <td colspan="8" class="text-center text-muted py-4">{{ __('No clinics found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clinics->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $clinics->links() }}
        </div>
    @endif
</div>
@endsection