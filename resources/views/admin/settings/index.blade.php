@extends('admin.layouts.app')

@section('title', __('Settings'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Settings') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Settings') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">{{ __('General Settings') }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Site Name (TK)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="site_name_tk" value="{{ old('site_name_tk', $setting->site_name_tk) }}" class="form-control @error('site_name_tk') is-invalid @enderror" required>
                    @error('site_name_tk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Site Name (RU)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="site_name_ru" value="{{ old('site_name_ru', $setting->site_name_ru) }}" class="form-control @error('site_name_ru') is-invalid @enderror" required>
                    @error('site_name_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Site Name (EN)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="site_name_en" value="{{ old('site_name_en', $setting->site_name_en) }}" class="form-control @error('site_name_en') is-invalid @enderror" required>
                    @error('site_name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Logo') }}</label>
                    @if($setting->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $setting->logo) }}" class="border rounded p-1 bg-light" height="40" alt="Logo">
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Favicon') }}</label>
                    @if($setting->favicon)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $setting->favicon) }}" class="border rounded p-1 bg-light" width="32" height="32" alt="Favicon">
                        </div>
                    @endif
                    <input type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror" accept=".ico,.png,.jpg,.svg">
                    @error('favicon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">{{ __('Support & Info') }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Phone 1') }}</label>
                    <input type="text" name="phone_1" value="{{ old('phone_1', $setting->phone_1) }}" class="form-control" placeholder="+993...">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Phone 2') }}</label>
                    <input type="text" name="phone_2" value="{{ old('phone_2', $setting->phone_2) }}" class="form-control" placeholder="+993...">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="form-control" placeholder="info@prodoctors.com.tm">
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">{{ __('Social Media & Messengers') }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Instagram</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-instagram"></i></span>
                        <input type="text" name="instagram" value="{{ old('instagram', $setting->instagram) }}" class="form-control" placeholder="@username">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">TikTok</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-tiktok"></i></span>
                        <input type="text" name="tiktok" value="{{ old('tiktok', $setting->tiktok) }}" class="form-control" placeholder="@username">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">IMO / Messenger</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-chat-dots"></i></span>
                        <input type="text" name="imo" value="{{ old('imo', $setting->imo) }}" class="form-control" placeholder="+993...">
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2 me-1"></i>{{ __('Save Settings') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection