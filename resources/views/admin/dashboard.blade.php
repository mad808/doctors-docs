@extends('admin.layouts.app')

@section('title', __('Dashboard'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Dashboard') }}</h4>
        <p class="text-muted small mb-0">{{ __('Welcome to ProDoctors Administration Panel') }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">{{ __('Total Doctors') }}</div>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats['doctors_count'] }}</h3>
                </div>
                <div class="bg-primary-subtle text-primary rounded-3 p-3">
                    <i class="bi bi-person-badge fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0 border-start border-info border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">{{ __('Total Patients') }}</div>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats['patients_count'] }}</h3>
                </div>
                <div class="bg-info-subtle text-info rounded-3 p-3">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0 border-start border-success border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">{{ __('Total Clinics') }}</div>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats['clinics_count'] }}</h3>
                </div>
                <div class="bg-success-subtle text-success rounded-3 p-3">
                    <i class="bi bi-hospital fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">{{ __('Total Appointments') }}</div>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats['appointments_count'] }}</h3>
                </div>
                <div class="bg-warning-subtle text-warning rounded-3 p-3">
                    <i class="bi bi-calendar-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($stats['pending_reviews'] > 0)
    <div class="alert alert-warning d-flex align-items-center justify-content-between shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>{{ __('Pending Reviews') }}: <strong>{{ $stats['pending_reviews'] }}</strong></span>
        </div>
        <a href="{{ route('admin.reviews.index', ['status' => '0']) }}" class="btn btn-sm btn-warning fw-semibold">
            {{ __('View All') }}
        </a>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">{{ __('Latest Appointments') }}</h6>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">{{ __('View All') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>{{ __('Patient') }}</th>
                            <th>{{ __('Doctor') }}</th>
                            <th>{{ __('Date & Time') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latest_appointments as $app)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $app->patient?->name ?? '—' }}</div>
                                    <div class="text-muted small">{{ $app->patient?->phone ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $app->doctorProfile?->user?->name ?? '—' }}</div>
                                </td>
                                <td class="small">
                                    <div>{{ \Carbon\Carbon::parse($app->appointment_date)->format('d.m.Y') }}</div>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($app->appointment_time)->format('H:i') }}</div>
                                </td>
                                <td>
                                    @php
                                        $badge = match($app->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'confirmed' => 'bg-primary text-white',
                                            'completed' => 'bg-success text-white',
                                            'cancelled' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($app->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">{{ __('No appointments found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">{{ __('Latest Reviews') }}</h6>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">{{ __('View All') }}</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($latest_reviews as $rev)
                        <li class="list-group-item p-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-semibold small">{{ $rev->user?->name ?? 'Patient' }}</span>
                                <span class="badge bg-warning-subtle text-warning-emphasis border">
                                    <i class="bi bi-star-fill text-warning me-1"></i>{{ $rev->rating }}/5
                                </span>
                            </div>
                            <p class="text-muted small mb-1 text-truncate">{{ $rev->comment }}</p>
                            <div class="d-flex align-items-center justify-content-between small text-muted">
                                <span>{{ $rev->doctorProfile?->user?->name ?? $rev->clinic?->name_tk ?? '—' }}</span>
                                <a href="{{ route('admin.reviews.show', $rev) }}" class="text-decoration-none">{{ __('Details') }}</a>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">{{ __('No reviews found.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection