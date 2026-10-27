<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = $site_settings?->{'site_name_' . app()->getLocale()} ?? $site_settings?->site_name_tk ?? 'ProDoctors';
    @endphp

    <title>@yield('title', $siteName . ' - Lukmanlar')</title>

    @if($site_settings?->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $site_settings->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icons/1.png') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">

    <style>
        :root {
            --pd-primary: #0284c7;
            --pd-primary-hover: #0369a1;
            --pd-secondary: #0f172a;
            --pd-bg-soft: #f8fafc;
            --pd-border: #e2e8f0;
        }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
            background-color: var(--pd-bg-soft);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        .btn-primary {
            background-color: var(--pd-primary);
            border-color: var(--pd-primary);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--pd-primary-hover);
            border-color: var(--pd-primary-hover);
        }
        .text-primary-custom {
            color: var(--pd-primary) !important;
        }
        .bg-primary-custom {
            background-color: var(--pd-primary) !important;
        }
        .card {
            border-radius: 12px;
            border: 1px solid var(--pd-border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .rating-badge {
            background-color: #ecfdf5;
            color: #047857;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('front.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('front.partials.popup')

    @include('front.partials.footer')

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3500"
        };

        @if(session('success'))
            toastr.success(@json(session('success')));
        @endif
        @if(session('error'))
            toastr.error(@json(session('error')));
        @endif
        @if($errors->any())
            toastr.error(@json($errors->first()));
        @endif
    </script>
    @stack('scripts')
</body>
</html>