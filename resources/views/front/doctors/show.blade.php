@extends('front.layouts.app')

@section('title', ($doctor->user?->name) . ' - ProDoctors')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}">{{ __('Doctors') }}</a></li>
            <li class="breadcrumb-item active">{{ $doctor->user?->name }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 mb-4 p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative flex-shrink-0" style="width: 90px; height: 90px;">
                    @if($doctor->user?->avatar)
                        <img src="{{ asset('storage/' . $doctor->user->avatar) }}" class="rounded-circle border object-fit-cover w-100 h-100 shadow-sm" alt="Doc">
                    @else
                        <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center w-100 h-100 fs-2 shadow-sm">
                            {{ strtoupper(substr($doctor->user?->name ?? 'D', 0, 1)) }}
                        </div>
                    @endif
                    @if($doctor->is_verified)
                        <i class="bi bi-patch-check-fill text-primary position-absolute bottom-0 end-0 bg-white rounded-circle fs-5 shadow-sm" title="{{ __('Verified Specialist') }}"></i>
                    @endif
                </div>

                <div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $doctor->user?->name }}</h3>
                    <div class="text-muted small mb-2">{{ $doctor->{'title_' . app()->getLocale()} ?? $doctor->title_tk }}</div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($doctor->specialties as $sp)
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $sp->{'name_' . app()->getLocale()} ?? $sp->name_tk }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 border-top border-lg-top-0 pt-3 pt-lg-0">
                @if($doctor->user?->phone)
                    <a href="tel:{{ $doctor->user->phone }}" class="btn btn-outline-success d-flex align-items-center gap-2 px-3 py-2 fw-semibold shadow-sm">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $doctor->user->phone }}</span>
                    </a>
                @endif

                <div class="d-flex flex-column align-items-lg-end">
                    <span class="rating-badge fs-6 px-3 py-1 mb-1">
                        <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($doctor->rating, 1) }}
                        <span class="text-muted fw-normal ms-1">({{ $doctor->reviews_count }} {{ __('reviews') }})</span>
                    </span>
                    <span class="fw-semibold text-secondary small">{{ $doctor->experience_years }} {{ __('years') }} {{ __('Experience') }}</span>
                    @if($doctor->consultation_fee)
                        <span class="fw-bold text-primary-custom fs-5">{{ number_format($doctor->consultation_fee, 2) }} TMT</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">{{ __('About Doctor') }}</h5>
                    <p class="text-secondary leading-relaxed mb-4">
                        {{ $doctor->{'bio_' . app()->getLocale()} ?? $doctor->bio_tk ?? __('No bio provided.') }}
                    </p>

                    <h6 class="fw-bold mb-2">{{ __('Education') }}</h6>
                    <p class="text-secondary small mb-0">
                        {{ $doctor->{'education_' . app()->getLocale()} ?? $doctor->education_tk ?? __('No education info provided.') }}
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0">{{ __('Patient Reviews') }} ({{ $doctor->reviews->count() }})</h5>
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4">
                        @forelse($doctor->reviews as $rev)
                            <div class="p-3 bg-light rounded border">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-bold small">{{ $rev->user?->name ?? 'Patient' }}</span>
                                    <span class="rating-badge small">
                                        <i class="bi bi-star-fill text-warning"></i>{{ $rev->rating }}/5
                                    </span>
                                </div>
                                <div class="text-muted very-small mb-2">{{ $rev->created_at->format('d.m.Y') }}</div>
                                <p class="text-secondary small mb-0">{{ $rev->comment }}</p>
                                @if($rev->admin_reply)
                                    <div class="mt-2 p-2 bg-white rounded border small text-info">
                                        <i class="bi bi-reply-fill me-1"></i>{{ $rev->admin_reply }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-muted small text-center py-3">{{ __('No reviews found.') }}</div>
                        @endforelse
                    </div>

                    @auth
                        @if(auth()->user()->isPatient())
                            <h6 class="fw-bold mb-2">{{ __('Leave a Review') }}</h6>
                            <form action="{{ route('patient.portal.reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctor_profile_id" value="{{ $doctor->id }}">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">{{ __('Select Rating') }}</label>
                                    <select name="rating" class="form-select form-select-sm" required>
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★☆</option>
                                        <option value="3">3 ★★★☆☆</option>
                                        <option value="2">2 ★★☆☆☆</option>
                                        <option value="1">1 ★☆☆☆☆</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" rows="3" class="form-control" placeholder="{{ __('Your Feedback') }}" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">{{ __('Submit Review') }}</button>
                            </form>
                        @endif
                    @else
                        <div class="text-center p-3 bg-light rounded">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">{{ __('Login to Leave a Review') }}</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-primary-custom">
                        <i class="bi bi-calendar2-check me-1"></i>{{ __('Book an Appointment') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @auth
                        @if(auth()->user()->isPatient())
                            <form action="{{ route('patient.portal.appointments.store') }}" method="POST" id="appointmentBookingForm">
                                @csrf
                                <input type="hidden" name="doctor_profile_id" value="{{ $doctor->id }}">
                                <input type="hidden" name="appointment_time" id="selected_time_slot" required>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">{{ __('Select Clinic') }}</label>
                                    <select name="clinic_id" class="form-select select2-basic" required>
                                        @foreach($doctor->clinics as $cl)
                                            <option value="{{ $cl->id }}">{{ $cl->{'name_' . app()->getLocale()} ?? $cl->name_tk }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">{{ __('Appointment Date') }}</label>
                                    <input type="date" id="appointment_date_picker" name="appointment_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label fw-semibold small mb-0">{{ __('Available Time Slots') }}</label>
                                        <span id="slotsLoadingSpinner" class="spinner-border spinner-border-sm text-primary d-none"></span>
                                    </div>
                                    <div id="time_slots_grid" class="d-flex flex-wrap gap-2">
                                        <!-- Generated by JS -->
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">{{ __('Symptoms or reason for visit') }}</label>
                                    <textarea name="symptoms_note" rows="2" class="form-control" placeholder="{{ __('Symptoms or reason for visit') }}"></textarea>
                                </div>

                                <button type="submit" id="btnSubmitBooking" class="btn btn-primary w-100 py-2 fw-semibold" disabled>
                                    {{ __('Confirm Booking') }}
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info small mb-0">
                                {{ __('Only patient accounts can book appointments.') }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted small mb-3">{{ __('Please login to choose a date and confirm your appointment.') }}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 py-2 fw-semibold">
                                {{ __('Login to Book') }}
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="card shadow-sm border-0 text-center p-4 mt-4">
                <h6 class="fw-bold mb-2">{{ __('Share Profile') }} / {{ __('Scan QR Code') }}</h6>
                <p class="text-muted small mb-3">{{ __('Scan to open and share this profile') }}</p>

                <div class="d-flex justify-content-center mb-3">
                    <div id="doctorProfileQrCode" class="p-3 bg-white border rounded-3 shadow-sm d-inline-block"></div>
                </div>

                <div class="input-group">
                    <input type="text" id="shareUrlInput" class="form-control form-control-sm bg-light" value="{{ url()->current() }}" readonly>
                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="copyShareLink()">
                        <i class="bi bi-clipboard me-1"></i>{{ __('Copy Link') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/cdn.min.js') }}"></script>
<script>
    $('.select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    const qrContainer = document.getElementById("doctorProfileQrCode");
    if (typeof QRCode !== 'undefined') {
        new QRCode(qrContainer, {
            text: "{{ url()->current() }}",
            width: 180,
            height: 180,
            colorDark: "#0f172a",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    } else {
        qrContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h3v3H7zm7 0h3v3h-3zm-7 7h3v3H7zm7 3v-3h3v3z"/></svg>`;
    }

    function copyShareLink() {
        const copyText = document.getElementById("shareUrlInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        toastr.success(@json(__('Link copied to clipboard!')));
    }

    const defaultSlots = [
        "09:00", "09:30", "10:00", "10:30", 
        "11:00", "11:30", "14:00", "14:30", 
        "15:00", "15:30", "16:00", "16:30", "17:00"
    ];

    function fetchDoctorSlots(selectedDate) {
        if (!selectedDate) return;

        $('#slotsLoadingSpinner').removeClass('d-none');
        $('#selected_time_slot').val('');
        $('#btnSubmitBooking').prop('disabled', true);

        $.ajax({
            url: "{{ route('doctors.booked-slots', $doctor) }}",
            type: "GET",
            data: { date: selectedDate },
            success: function(res) {
                $('#slotsLoadingSpinner').addClass('d-none');
                const booked = res.booked_slots || [];
                const $grid = $('#time_slots_grid');
                $grid.empty();

                defaultSlots.forEach(function(time) {
                    const isBooked = booked.includes(time);
                    if (isBooked) {
                        $grid.append(`
                            <button type="button" class="btn btn-sm btn-light border text-muted opacity-50 px-3 py-1" style="text-decoration: line-through;" disabled title="Booked">
                                ${time}
                            </button>
                        `);
                    } else {
                        $grid.append(`
                            <button type="button" class="btn btn-sm btn-outline-primary time-slot-btn px-3 py-1 fw-semibold" data-time="${time}">
                                ${time}
                            </button>
                        `);
                    }
                });
            },
            error: function() {
                $('#slotsLoadingSpinner').addClass('d-none');
            }
        });
    }

    $('#appointment_date_picker').on('change', function() {
        fetchDoctorSlots($(this).val());
    });

    $(document).on('click', '.time-slot-btn', function() {
        $('.time-slot-btn').removeClass('btn-primary text-white').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary text-white');

        const chosenTime = $(this).data('time');
        $('#selected_time_slot').val(chosenTime);
        $('#btnSubmitBooking').prop('disabled', false);
    });

    if ($('#appointment_date_picker').length) {
        fetchDoctorSlots($('#appointment_date_picker').val());
    }
</script>
@endpush