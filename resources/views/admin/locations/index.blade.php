@extends('admin.layouts.app')

@section('title', __('Locations'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Locations') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Locations') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>{{ __('Add Location') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th scope="col" style="width: 70px;">ID</th>
                    <th scope="col">{{ __('Name') }} (TK / RU / EN)</th>
                    <th scope="col">{{ __('Parent') }}</th>
                    <th scope="col">{{ __('Type') }}</th>
                    <th scope="col">{{ __('Clinics Count') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td class="fw-bold">#{{ $location->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $location->{'name_' . app()->getLocale()} ?? $location->name_tk }}</div>
                            <div class="text-muted small">
                                {{ $location->name_tk }} / {{ $location->name_ru }} / {{ $location->name_en }}
                            </div>
                        </td>
                        <td>
                            @if($location->parent)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-geo-alt me-1 text-primary"></i>{{ $location->parent->{'name_' . app()->getLocale()} ?? $location->parent->name_tk }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if(is_null($location->parent_id))
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ __('Region') }}</span>
                            @else
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">{{ __('City / District') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis border">
                                {{ $location->clinics_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            @if($location->status == 1)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this location?') }}')">
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
                        <td colspan="7" class="text-center text-muted py-4">{{ __('No locations found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($locations, 'hasPages') && $locations->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $locations->links() }}
        </div>
    @endif
</div>
@endsection