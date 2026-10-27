@extends('front.layouts.app')

@section('title', '404 - ' . __('Page Not Found'))

@section('content')
<div class="container py-5 text-center my-auto">
    <div class="py-5">
        <h1 class="display-1 fw-bold text-primary-custom mb-2">404</h1>
        <h3 class="fw-bold mb-3">{{ __('Sahypa tapylmady') }} / {{ __('Page Not Found') }}</h3>
        <p class="text-muted mb-4">{{ __('Gözleýän sahypaňyz ýok ýa-da başga ýere geçirilen bolmagy mümkin.') }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 fw-semibold">
            <i class="bi bi-house-door me-1"></i>{{ __('Home') }}
        </a>
    </div>
</div>
@endsection