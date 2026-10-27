@extends('admin.layouts.app')

@section('title', __('Edit Specialty'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Edit Specialty') }} #{{ $specialty->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.specialties.index') }}">{{ __('Specialties') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.specialties.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.specialties.update', $specialty) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Specialty Name (TK)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_tk" value="{{ old('name_tk', $specialty->name_tk) }}" class="form-control @error('name_tk') is-invalid @enderror" required>
                    @error('name_tk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Specialty Name (RU)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_ru" value="{{ old('name_ru', $specialty->name_ru) }}" class="form-control @error('name_ru') is-invalid @enderror" required>
                    @error('name_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Specialty Name (EN)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en', $specialty->name_en) }}" class="form-control @error('name_en') is-invalid @enderror" required>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Icon Class') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="{{ $specialty->icon ?? 'bi bi-heart-pulse' }}"></i></span>
                        <input type="text" name="icon" value="{{ old('icon', $specialty->icon) }}" class="form-control @error('icon') is-invalid @enderror">
                    </div>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="specialtyStatus" {{ old('status', $specialty->status) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="specialtyStatus">{{ __('Active') }}</label>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="{{ route('admin.specialties.index') }}" class="btn btn-light me-2">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection