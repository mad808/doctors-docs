@extends('admin.layouts.app')

@section('title', __('Edit Doctor'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Edit Doctor') }} #{{ $doctor->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.doctors.index') }}">{{ __('Doctors') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">{{ __('User & Authentication') }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $doctor->user?->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $doctor->user?->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('New Password') }} <span class="text-muted small">({{ __('Leave blank to keep current') }})</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Avatar') }}</label>
                    @if($doctor->user?->avatar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $doctor->user->avatar) }}" class="rounded-circle border object-fit-cover" width="50" height="50" alt="Avatar">
                        </div>
                    @endif
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">{{ __('Doctor Details & Specialization') }}</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Title / Qualification (TK)') }}</label>
                    <input type="text" name="title_tk" value="{{ old('title_tk', $doctor->title_tk) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Title / Qualification (RU)') }}</label>
                    <input type="text" name="title_ru" value="{{ old('title_ru', $doctor->title_ru) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Title / Qualification (EN)') }}</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $doctor->title_en) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Experience (Years)') }} <span class="text-danger">*</span></label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}" min="0" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Consultation Fee (TMT)') }}</label>
                    <input type="number" step="0.01" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0" class="form-control">
                </div>

                @php
                    $selectedSpecialties = old('specialties', $doctor->specialties->pluck('id')->toArray());
                    $selectedClinics = old('clinics', $doctor->clinics->pluck('id')->toArray());
                @endphp

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Specialties') }} <span class="text-danger">*</span></label>
                    <select name="specialties[]" class="form-select select2-multiple @error('specialties') is-invalid @enderror" multiple required>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ in_array($specialty->id, $selectedSpecialties) ? 'selected' : '' }}>
                                {{ $specialty->{'name_' . app()->getLocale()} ?? $specialty->name_tk }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialties')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ __('Clinics / Workplaces') }}</label>
                    <select name="clinics[]" class="form-select select2-multiple" multiple>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ in_array($clinic->id, $selectedClinics) ? 'selected' : '' }}>
                                {{ $clinic->{'name_' . app()->getLocale()} ?? $clinic->name_tk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Education (TK)') }}</label>
                    <textarea name="education_tk" rows="3" class="form-control">{{ old('education_tk', $doctor->education_tk) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Education (RU)') }}</label>
                    <textarea name="education_ru" rows="3" class="form-control">{{ old('education_ru', $doctor->education_ru) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Education (EN)') }}</label>
                    <textarea name="education_en" rows="3" class="form-control">{{ old('education_en', $doctor->education_en) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Bio / Description (TK)') }}</label>
                    <textarea name="bio_tk" rows="4" class="form-control">{{ old('bio_tk', $doctor->bio_tk) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Bio / Description (RU)') }}</label>
                    <textarea name="bio_ru" rows="4" class="form-control">{{ old('bio_ru', $doctor->bio_ru) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Bio / Description (EN)') }}</label>
                    <textarea name="bio_en" rows="4" class="form-control">{{ old('bio_en', $doctor->bio_en) }}</textarea>
                </div>

                <div class="col-12 d-flex gap-4 mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="verifySwitch" {{ old('is_verified', $doctor->is_verified) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="verifySwitch">{{ __('Verified Doctor') }}</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" {{ old('status', $doctor->status) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="statusSwitch">{{ __('Active') }}</label>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-light me-2">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
</script>
@endpush