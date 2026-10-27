@extends('admin.layouts.app')

@section('title', __('Specialties'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Specialties') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Specialties') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.specialties.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>{{ __('Add Specialty') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th scope="col" style="width: 70px;">ID</th>
                    <th scope="col" style="width: 60px;">{{ __('Icon') }}</th>
                    <th scope="col">{{ __('Name') }} (TK / RU / EN)</th>
                    <th scope="col">{{ __('Doctors Count') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($specialties as $specialty)
                    <tr>
                        <td class="fw-bold">#{{ $specialty->id }}</td>
                        <td>
                            <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                @if($specialty->icon)
                                    <i class="{{ $specialty->icon }} fs-5 text-primary"></i>
                                @else
                                    <i class="bi bi-heart-pulse fs-5 text-secondary"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $specialty->{'name_' . app()->getLocale()} ?? $specialty->name_tk }}</div>
                            <div class="text-muted small">
                                {{ $specialty->name_tk }} / {{ $specialty->name_ru }} / {{ $specialty->name_en }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                <i class="bi bi-person-badge me-1"></i>{{ $specialty->doctors_count }}
                            </span>
                        </td>
                        <td>
                            @if($specialty->status == 1)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.specialties.edit', $specialty) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.specialties.destroy', $specialty) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this specialty?') }}')">
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
                        <td colspan="6" class="text-center text-muted py-4">{{ __('No specialties found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($specialties->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $specialties->links() }}
        </div>
    @endif
</div>
@endsection