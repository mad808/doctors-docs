@extends('admin.layouts.app')

@section('title', __('Banners'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Banners') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Banners') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>{{ __('Add Banner') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th scope="col" style="width: 70px;">{{ __('Order') }}</th>
                    <th scope="col" style="width: 140px;">{{ __('Image') }}</th>
                    <th scope="col">{{ __('Title') }} (TK / RU / EN)</th>
                    <th scope="col">{{ __('Link') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td class="fw-bold text-center">{{ $banner->order }}</td>
                        <td>
                            @if($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" class="rounded border object-fit-cover" width="120" height="55" alt="Banner">
                            @else
                                <span class="text-muted small">{{ __('No Image') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $banner->title_tk ?: '—' }}</div>
                            <div class="text-muted small">{{ $banner->title_ru ?: '—' }} | {{ $banner->title_en ?: '—' }}</div>
                        </td>
                        <td>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" target="_blank" class="small text-truncate d-inline-block" style="max-width: 220px;">{{ $banner->link }}</a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($banner->status == 1)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this banner?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">{{ __('No banners found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($banners->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $banners->links() }}
        </div>
    @endif
</div>
@endsection