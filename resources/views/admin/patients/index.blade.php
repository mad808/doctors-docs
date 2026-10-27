@extends('admin.layouts.app')

@section('title', __('Patients'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Patients') }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Patients') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>{{ __('Add Patient') }}
    </a>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.patients.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or phone...') }}" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            @if(request()->filled('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Reset') }}</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th scope="col" style="width: 70px;">{{ __('Avatar') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Phone') }}</th>
                    <th scope="col">{{ __('Email') }}</th>
                    <th scope="col">{{ __('Created At') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>
                            @if($patient->avatar)
                                <img src="{{ asset('storage/' . $patient->avatar) }}" class="rounded-circle border object-fit-cover" width="44" height="44" alt="Patient">
                            @else
                                <div class="bg-primary-subtle text-primary border rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                                    {{ strtoupper(substr($patient->name ?? 'P', 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $patient->name }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td class="text-muted small">{{ $patient->email ?: '—' }}</td>
                        <td class="small text-muted">{{ $patient->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($patient->status == 1)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this patient?') }}')">
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
                        <td colspan="7" class="text-center text-muted py-4">{{ __('No patients found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection