@extends('admin.layouts.app')

@section('title', __('Add Clinic'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Add Clinic') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.clinics.index') }}">{{ __('Clinics') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Add Clinic') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.clinics.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.clinics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Clinic Name (TK)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_tk" value="{{ old('name_tk') }}" class="form-control @error('name_tk') is-invalid @enderror" required>
                    @error('name_tk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Clinic Name (RU)') }}</label>
                    <input type="text" name="name_ru" value="{{ old('name_ru') }}" class="form-control @error('name_ru') is-invalid @enderror">
                    @error('name_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Clinic Name (EN)') }}</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" class="form-control @error('name_en') is-invalid @enderror">
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Location (City)') }} <span class="text-danger">*</span></label>
                    <select name="location_id" class="form-select select2-basic @error('location_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('Select Location') }} --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->{'name_' . app()->getLocale()} ?? $loc->name_tk }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Phone 1') }}</label>
                    <input type="text" name="phone_1" value="{{ old('phone_1') }}" class="form-control @error('phone_1') is-invalid @enderror" placeholder="+993...">
                    @error('phone_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Phone 2') }}</label>
                    <input type="text" name="phone_2" value="{{ old('phone_2') }}" class="form-control @error('phone_2') is-invalid @enderror" placeholder="+993...">
                    @error('phone_2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Address (TK)') }}</label>
                    <input type="text" name="address_tk" value="{{ old('address_tk') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Address (RU)') }}</label>
                    <input type="text" name="address_ru" value="{{ old('address_ru') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Address (EN)') }}</label>
                    <input type="text" name="address_en" value="{{ old('address_en') }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Website') }}</label>
                    <input type="url" name="website" value="{{ old('website') }}" class="form-control" placeholder="https://...">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold small">{{ __('Latitude') }}</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude') }}" class="form-control" placeholder="37.9600">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold small">{{ __('Longitude') }}</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude') }}" class="form-control" placeholder="58.3261">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Logo') }}</label>
                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Cover Image') }}</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Description (TK)') }}</label>
                    <textarea name="description_tk" rows="3" class="form-control">{{ old('description_tk') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Description (RU)') }}</label>
                    <textarea name="description_ru" rows="3" class="form-control">{{ old('description_ru') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Description (EN)') }}</label>
                    <textarea name="description_en" rows="3" class="form-control">{{ old('description_en') }}</textarea>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="clinicStatus" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="clinicStatus">{{ __('Active') }}</label>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="{{ route('admin.clinics.index') }}" class="btn btn-light me-2">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
</script>
@endpush