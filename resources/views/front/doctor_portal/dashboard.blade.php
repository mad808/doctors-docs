@extends('front.layouts.app')

@section('title', __('Doctor Portal') . ' - ProDoctors')

@section('content')
<div class="container py-4">
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-3">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle border object-fit-cover shadow-sm" width="68" height="68" alt="Doctor">
                @else
                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center fs-3 shadow-sm" style="width: 68px; height: 68px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                    <div class="text-muted small">{{ $profile->{'title_' . app()->getLocale()} ?? $profile->title_tk }}</div>
                    <div class="small text-primary mt-1"><i class="bi bi-telephone me-1"></i>{{ auth()->user()->phone }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('doctors.show', $profile) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-box-arrow-up-right me-1"></i>{{ __('Doctor Profile') }}
            </a>
            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($profile->rating, 1) }} / 5.0
            </span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="text-muted small fw-semibold">{{ __('Total Patients Managed') }}</div>
                <h3 class="fw-bold mb-0 mt-1">{{ $appointments->total() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="text-muted small fw-semibold">{{ __('Experience') }}</div>
                <h3 class="fw-bold mb-0 mt-1">{{ $profile->experience_years }} {{ __('years') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="text-muted small fw-semibold">{{ __('Consultation Fee') }}</div>
                <h3 class="fw-bold mb-0 mt-1">{{ $profile->consultation_fee ? number_format($profile->consultation_fee, 2) . ' TMT' : '—' }}</h3>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">{{ __('My Appointments Schedule') }}</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('Patient') }}</th>
                        <th scope="col">{{ __('Clinic') }}</th>
                        <th scope="col">{{ __('Date & Time') }}</th>
                        <th scope="col">{{ __('Patient Note') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col" class="text-end" style="min-width: 170px;">{{ __('Change Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $app)
                        <tr>
                            <td class="fw-bold">#{{ $app->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $app->patient?->name ?? '—' }}</div>
                                <div class="text-muted small">
                                    <a href="tel:{{ $app->patient?->phone }}" class="text-decoration-none text-muted">
                                        <i class="bi bi-telephone me-1"></i>{{ $app->patient?->phone ?? '—' }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                {{ $app->clinic?->{'name_' . app()->getLocale()} ?? $app->clinic?->name_tk ?? '—' }}
                            </td>
                            <td>
                                <div><i class="bi bi-calendar-event me-1 text-muted"></i>{{ \Carbon\Carbon::parse($app->appointment_date)->format('d.m.Y') }}</div>
                                <div class="small text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($app->appointment_time)->format('H:i') }}</div>
                            </td>
                            <td style="max-width: 250px;">
                                <div class="text-truncate small" title="{{ $app->symptoms_note }}">
                                    {{ $app->symptoms_note ?: '—' }}
                                </div>
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
                            <td class="text-end">
                                <form action="{{ route('doctor.portal.appointments.update', $app) }}" method="POST" class="d-inline-flex justify-content-end">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                                        <option value="pending" {{ $app->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="confirmed" {{ $app->status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                        <option value="completed" {{ $app->status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                        <option value="cancelled" {{ $app->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ __('No appointments scheduled yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection