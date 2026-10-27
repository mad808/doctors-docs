@extends('admin.layouts.app')

@section('title', __('Edit Banner'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Edit Banner') }} #{{ $banner->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">{{ __('Banners') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold small">{{ __('Current Image') }}</label>
                    <div class="mb-2">
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" class="rounded border object-fit-cover" width="240" height="100" alt="Banner">
                        @else
                            <span class="text-muted small">{{ __('No Image') }}</span>
                        @endif
                    </div>
                    <label class="form-label fw-semibold small">{{ __('Change Image') }}</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Title (TK)') }}</label>
                    <input type="text" name="title_tk" value="{{ old('title_tk', $banner->title_tk) }}" class="form-control @error('title_tk') is-invalid @enderror">
                    @error('title_tk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Title (RU)') }}</label>
                    <input type="text" name="title_ru" value="{{ old('title_ru', $banner->title_ru) }}" class="form-control @error('title_ru') is-invalid @enderror">
                    @error('title_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Title (EN)') }}</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $banner->title_en) }}" class="form-control @error('title_en') is-invalid @enderror">
                    @error('title_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold small">{{ __('Link URL') }}</label>
                    <input type="url" name="link" value="{{ old('link', $banner->link) }}" class="form-control @error('link') is-invalid @enderror" placeholder="https://...">
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">{{ __('Order') }} <span class="text-danger">*</span></label>
                    <input type="number" name="order" value="{{ old('order', $banner->order) }}" min="0" class="form-control @error('order') is-invalid @enderror" required>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" {{ old('status', $banner->status) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="statusSwitch">{{ __('Active') }}</label>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-light me-2">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection