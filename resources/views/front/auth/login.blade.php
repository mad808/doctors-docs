@extends('front.layouts.app')

@section('title', __('Login') . ' - ProDoctors')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-primary-custom rounded-circle mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-shield-lock fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ __('Login or Register') }}</h4>
                        <p class="text-muted small">{{ __('Enter your phone number to receive a 6-digit confirmation code') }}</p>
                    </div>

                    <div id="step-phone">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">{{ __('Phone Number') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">+993</span>
                                <input type="tel" id="phone_input" class="form-control" placeholder="6XXXXXXX" maxlength="8" autofocus>
                            </div>
                            <div class="form-text small">{{ __('Format: 8 digits (e.g. 65123456)') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">{{ __('Full Name') }} ({{ __('Optional for new users') }})</label>
                            <input type="text" id="name_input" class="form-control" placeholder="{{ __('Myrat Myradow') }}">
                        </div>

                        <button type="button" id="btn-send-otp" class="btn btn-primary w-100 py-2 fw-semibold">
                            <span>{{ __('Send Code') }}</span>
                            <span class="spinner-border spinner-border-sm d-none ms-1"></span>
                        </button>
                    </div>

                    <div id="step-otp" class="d-none">
                        <div class="alert alert-info py-2 small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            {{ __('Code sent to') }}: <span id="target_phone" class="fw-bold"></span>
                            <a href="javascript:void(0)" id="btn-change-phone" class="ms-2 text-decoration-none fw-semibold">{{ __('Change') }}</a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">{{ __('Verification Code') }}</label>
                            <input type="text" id="otp_code_input" class="form-control text-center fs-4 letter-spacing-2" placeholder="••••••" maxlength="6" inputmode="numeric">
                        </div>

                        <button type="button" id="btn-verify-otp" class="btn btn-primary w-100 py-2 fw-semibold mb-2">
                            <span>{{ __('Verify & Continue') }}</span>
                            <span class="spinner-border spinner-border-sm d-none ms-1"></span>
                        </button>

                        <button type="button" id="btn-resend-otp" class="btn btn-link w-100 text-decoration-none text-muted small" disabled>
                            {{ __('Resend Code') }} <span id="countdown">(60s)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let normalizedPhone = '';
    let timerInterval = null;

    $('#btn-send-otp').on('click', function () {
        const raw = $('#phone_input').val().trim();
        const name = $('#name_input').val().trim();

        if (raw.length !== 8) {
            toastr.error(@json(__('Please enter a valid 8-digit phone number')));
            return;
        }

        normalizedPhone = '+993' + raw;
        const $btn = $(this);
        $btn.prop('disabled', true);
        $btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: @json(route('auth.send-otp')),
            method: 'POST',
            data: {
                phone: normalizedPhone,
                name: name
            },
            success: function (res) {
                $btn.prop('disabled', false);
                $btn.find('.spinner-border').addClass('d-none');

                if (res.success) {
                    toastr.success(res.message);
                    $('#target_phone').text(normalizedPhone);
                    $('#step-phone').addClass('d-none');
                    $('#step-otp').removeClass('d-none');
                    $('#otp_code_input').focus();
                    if (res.code) {
                        $('#otp_code_input').val(res.code);
                    }
                    startCountdown(60);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (xhr) {
                $btn.prop('disabled', false);
                $btn.find('.spinner-border').addClass('d-none');
                const msg = xhr.responseJSON ? xhr.responseJSON.message : @json(__('An error occurred. Please try again.'));
                toastr.error(msg);
            }
        });
    });

    $('#btn-verify-otp').on('click', function () {
        const code = $('#otp_code_input').val().trim();

        if (code.length !== 6) {
            toastr.error(@json(__('Please enter 6-digit code')));
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true);
        $btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: @json(route('auth.verify-otp')),
            method: 'POST',
            data: {
                phone: normalizedPhone,
                code: code
            },
            success: function (res) {
                if (res.success) {
                    toastr.success(@json(__('Login successful')));
                    window.location.href = res.redirect || @json(route('home'));
                } else {
                    $btn.prop('disabled', false);
                    $btn.find('.spinner-border').addClass('d-none');
                    toastr.error(res.message);
                }
            },
            error: function (xhr) {
                $btn.prop('disabled', false);
                $btn.find('.spinner-border').addClass('d-none');
                const msg = xhr.responseJSON ? xhr.responseJSON.message : @json(__('Verification failed'));
                toastr.error(msg);
            }
        });
    });

    $('#btn-change-phone').on('click', function () {
        clearInterval(timerInterval);
        $('#step-otp').addClass('d-none');
        $('#step-phone').removeClass('d-none');
        $('#phone_input').focus();
    });

    $('#btn-resend-otp').on('click', function () {
        $('#btn-send-otp').trigger('click');
    });

    function startCountdown(sec) {
        let left = sec;
        const $btn = $('#btn-resend-otp');
        const $countdown = $('#countdown');
        $btn.prop('disabled', true);
        $countdown.text('(' + left + 's)');

        clearInterval(timerInterval);
        timerInterval = setInterval(function () {
            left--;
            if (left <= 0) {
                clearInterval(timerInterval);
                $btn.prop('disabled', false);
                $countdown.text('');
            } else {
                $countdown.text('(' + left + 's)');
            }
        }, 1000);
    }
</script>
@endpush