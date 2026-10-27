@extends('admin.layouts.app')

@section('title', __('Edit Location'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Edit Location') }} #{{ $location->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}">{{ __('Locations') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.locations.update', $location) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold small">{{ __('Parent Region') }}</label>
                    <select name="parent_id" class="form-select select2-basic @error('parent_id') is-invalid @enderror">
                        <option value="">{{ __('None (Top Level Region)') }}</option>
                        @foreach($parents ?? [] as $parent)
                            @if($parent->id !== $location->id)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $location->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->{'name_' . app()->getLocale()} ?? $parent->name_tk }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Location Name (TK)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_tk" value="{{ old('name_tk', $location->name_tk) }}" class="form-control @error('name_tk') is-invalid @enderror" required>
                    @error('name_tk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Location Name (RU)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_ru" value="{{ old('name_ru', $location->name_ru) }}" class="form-control @error('name_ru') is-invalid @enderror" required>
                    @error('name_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Location Name (EN)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en', $location->name_en) }}" class="form-control @error('name_en') is-invalid @enderror" required>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="locationStatus" {{ old('status', $location->status) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="locationStatus">{{ __('Active') }}</label>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-light me-2">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Update') }}</button>
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