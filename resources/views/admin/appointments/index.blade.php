@extends('admin.layouts.app')

@section('title', __('Appointments'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Appointments') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Appointments') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-2 align-items-center">
            <div class="col-sm-4 col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- {{ __('All Statuses') }} --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            @if(request()->has('status'))
                <div class="col-auto">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Reset') }}</a>
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
                    <th scope="col">ID</th>
                    <th scope="col">{{ __('Patient') }}</th>
                    <th scope="col">{{ __('Doctor') }}</th>
                    <th scope="col">{{ __('Clinic') }}</th>
                    <th scope="col">{{ __('Date & Time') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td class="fw-bold">#{{ $appointment->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $appointment->patient?->name ?? '—' }}</div>
                            <div class="text-muted small">{{ $appointment->patient?->phone ?? '—' }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $appointment->doctorProfile?->user?->name ?? '—' }}</div>
                            <div class="text-muted small">
                                {{ $appointment->doctorProfile?->{'title_' . app()->getLocale()} ?? $appointment->doctorProfile?->title_tk }}
                            </div>
                        </td>
                        <td>
                            {{ $appointment->clinic?->{'name_' . app()->getLocale()} ?? $appointment->clinic?->name_tk ?? '—' }}
                        </td>
                        <td>
                            <div><i class="bi bi-calendar-event me-1 text-muted"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y') }}</div>
                            <div class="small text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($appointment->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-primary text-white',
                                    'completed' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    default => 'bg-secondary text-white'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($appointment->status) }}</span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1 align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewNoteModal{{ $appointment->id }}" title="{{ __('Symptoms / Notes') }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item">{{ __('Pending') }}</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="dropdown-item text-primary">{{ __('Confirmed') }}</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item text-success">{{ __('Completed') }}</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item text-danger">{{ __('Cancelled') }}</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this appointment?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="modal fade" id="viewNoteModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog text-start">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">{{ __('Appointment Details') }} #{{ $appointment->id }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div class="text-muted small">{{ __('Patient Symptoms / Note') }}</div>
                                                <div class="p-2 mt-1 bg-light rounded border">
                                                    {{ $appointment->symptoms_note ?: __('No symptoms note provided.') }}
                                                </div>
                                            </div>
                                            <div class="row g-2 small">
                                                <div class="col-6">
                                                    <span class="text-muted">{{ __('Created At') }}:</span>
                                                    <span class="fw-semibold">{{ $appointment->created_at->format('d.m.Y H:i') }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{ __('Status') }}:</span>
                                                    <span class="fw-semibold">{{ ucfirst($appointment->status) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">{{ __('No appointments found.') }}</td>
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
@endsection