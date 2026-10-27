@extends('admin.layouts.app')

@section('title', __('Doctors'))

@push('styles')
<style>
    @keyframes pulseSlot {
        0% { transform: scale(0.96); opacity: 0.6; }
        50% { transform: scale(1.02); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }
    .slot-card-animated {
        animation: pulseSlot 0.3s ease-out forwards;
        transition: all 0.2s ease-in-out;
    }
    .slot-card-animated:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .modal.fade .modal-dialog {
        transition: transform 0.25s ease-out;
        transform: scale(0.95);
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Doctors') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Doctors') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>{{ __('Add Doctor') }}
    </a>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.doctors.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or phone...') }}" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            @if(request()->filled('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Reset') }}</a>
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
                    <th scope="col" style="width: 70px;">{{ __('Avatar') }}</th>
                    <th scope="col">{{ __('Doctor') }}</th>
                    <th scope="col">{{ __('Specialties') }}</th>
                    <th scope="col">{{ __('Clinics') }}</th>
                    <th scope="col">{{ __('Experience') }}</th>
                    <th scope="col">{{ __('Rating') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>
                            @if($doctor->user?->avatar)
                                <img src="{{ asset('storage/' . $doctor->user->avatar) }}" class="rounded-circle border object-fit-cover shadow-sm" width="48" height="48" alt="Doc">
                            @else
                                <div class="bg-primary-subtle text-primary border rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px;">
                                    {{ strtoupper(substr($doctor->user?->name ?? 'D', 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold d-flex align-items-center gap-1">
                                <span>{{ $doctor->user?->name ?? '—' }}</span>
                                @if($doctor->is_verified)
                                    <i class="bi bi-patch-check-fill text-primary" title="{{ __('Verified') }}"></i>
                                @endif
                            </div>
                            <div class="text-muted small">{{ $doctor->user?->phone ?? '—' }}</div>
                            <div class="text-muted small">{{ $doctor->{'title_' . app()->getLocale()} ?? $doctor->title_tk }}</div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($doctor->specialties as $sp)
                                    <span class="badge bg-light text-dark border">{{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}</span>
                                @empty
                                    <span class="text-muted small">—</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($doctor->clinics as $cl)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border">{{ $cl->{'name_' . app()->getLocale()} ?? $cl->name_tk }}</span>
                                @empty
                                    <span class="text-muted small">—</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <span class="small fw-semibold">{{ $doctor->experience_years }} {{ __('years') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($doctor->rating, 1) }}
                            </span>
                            <div class="text-muted small">{{ $doctor->reviews_count }} {{ __('reviews') }}</div>
                        </td>
                        <td>
                            @if($doctor->status == 1)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-info" title="{{ __('View Booked Schedule') }}" onclick="viewDoctorSchedule('{{ $doctor->id }}', '{{ addslashes($doctor->user?->name) }}')">
                                    <i class="bi bi-calendar3"></i>
                                </button>
                                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this doctor?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">{{ __('No doctors found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($doctors->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $doctors->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="adminScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-check-fill text-primary fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="scheduleModalTitle">{{ __('Doctor Schedule') }}</h5>
                        <div class="text-muted very-small" id="scheduleDoctorSubtitle"></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="card border-0 shadow-sm p-3 mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-sm-5">
                            <label class="form-label fw-semibold small mb-1">{{ __('Select Date') }}</label>
                            <input type="date" id="schedule_date_input" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-sm-7 text-sm-end mt-2 mt-sm-0 pt-sm-3">
                            <span class="badge bg-primary px-3 py-2" id="bookedCountBadge">0 {{ __('Booked Appointments') }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center py-4 d-none" id="scheduleLoadingSpinner">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <div id="scheduleSlotsContainer" class="row g-3">
                    <!-- Loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeDoctorId = null;

    function viewDoctorSchedule(doctorId, doctorName) {
        activeDoctorId = doctorId;
        $('#scheduleModalTitle').text(doctorName);
        $('#scheduleDoctorSubtitle').text(@json(__('Live Appointment Timetable')));

        const modal = new bootstrap.Modal(document.getElementById('adminScheduleModal'));
        modal.show();

        loadScheduleData();
    }

    $('#schedule_date_input').on('change', function () {
        loadScheduleData();
    });

    function loadScheduleData() {
        if (!activeDoctorId) return;

        const dateVal = $('#schedule_date_input').val();
        $('#scheduleLoadingSpinner').removeClass('d-none');
        $('#scheduleSlotsContainer').empty();

        const url = "{{ url('admin/doctors') }}/" + activeDoctorId + "/schedule";

        $.ajax({
            url: url,
            method: 'GET',
            data: { date: dateVal },
            success: function (res) {
                $('#scheduleLoadingSpinner').addClass('d-none');
                const apps = res.appointments || [];
                const $container = $('#scheduleSlotsContainer');

                $('#bookedCountBadge').text(apps.length + " " + @json(__('Booked Appointments')));

                if (apps.length === 0) {
                    $container.html(`
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 opacity-50 d-block mb-2"></i>
                            <h6>${@json(__('No bookings for this date.'))}</h6>
                            <small class="text-muted">${@json(__('All time slots are completely free.'))}</small>
                        </div>
                    `);
                    return;
                }

                apps.forEach(function (app, index) {
                    const time = app.appointment_time.substring(0, 5);
                    const patientName = app.patient ? app.patient.name : '—';
                    const patientPhone = app.patient ? app.patient.phone : '—';
                    const clinicName = app.clinic ? (app.clinic.name_{{ app()->getLocale() }} || app.clinic.name_tk) : '—';
                    const note = app.symptoms_note || @json(__('No notes'));

                    let badgeClass = 'bg-secondary';
                    if (app.status === 'pending') badgeClass = 'bg-warning text-dark';
                    if (app.status === 'confirmed') badgeClass = 'bg-primary text-white';
                    if (app.status === 'completed') badgeClass = 'bg-success text-white';
                    if (app.status === 'cancelled') badgeClass = 'bg-danger text-white';

                    $container.append(`
                        <div class="col-md-6 slot-card-animated" style="animation-delay: ${index * 0.05}s">
                            <div class="card h-100 border p-3 bg-white shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fs-5 fw-bold text-primary">
                                        <i class="bi bi-clock me-1"></i>${time}
                                    </span>
                                    <span class="badge ${badgeClass} text-uppercase small">${app.status}</span>
                                </div>
                                <div class="small fw-semibold text-dark mb-1">
                                    <i class="bi bi-person me-1 text-muted"></i>${patientName}
                                </div>
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-telephone me-1 text-muted"></i>${patientPhone}
                                </div>
                                <div class="small text-secondary mb-2">
                                    <i class="bi bi-hospital me-1 text-muted"></i>${clinicName}
                                </div>
                                <div class="p-2 bg-light rounded very-small text-muted border text-truncate" title="${note}">
                                    <i class="bi bi-chat-left-text me-1"></i>${note}
                                </div>
                            </div>
                        </div>
                    `);
                });
            },
            error: function () {
                $('#scheduleLoadingSpinner').addClass('d-none');
                toastr.error(@json(__('Failed to load doctor schedule.')));
            }
        });
    }
</script>
@endpush