@extends('front.layouts.app')

@section('title', __('Patient Dashboard') . ' - ProDoctors')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('Patient Dashboard') }}</h4>
            <div class="text-muted small">{{ auth()->user()->name }} ({{ auth()->user()->phone }})</div>
        </div>
        <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-search me-1"></i>{{ __('Book Doctor Now') }}
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">{{ __('My Bookings') }}</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('Doctor') }}</th>
                        <th scope="col">{{ __('Clinic') }}</th>
                        <th scope="col">{{ __('Date & Time') }}</th>
                        <th scope="col">{{ __('Symptoms / Notes') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $appointments = auth()->user()->appointments()->with(['doctorProfile.user', 'clinic'])->latest('appointment_date')->paginate(15);
                    @endphp
                    @forelse($appointments as $app)
                        <tr>
                            <td class="fw-bold">#{{ $app->id }}</td>
                            <td>
                                <div class="fw-semibold">
                                    <a href="{{ route('doctors.show', $app->doctorProfile) }}" class="text-decoration-none text-dark">
                                        {{ $app->doctorProfile?->user?->name ?? '—' }}
                                    </a>
                                </div>
                                <div class="text-muted small">{{ $app->doctorProfile?->{'title_' . app()->getLocale()} ?? $app->doctorProfile?->title_tk }}</div>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                {{ __('No bookings yet') }}
                            </td>
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