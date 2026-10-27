<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteTitle = $site_settings?->{'site_name_' . app()->getLocale()} ?? $site_settings?->site_name_tk ?? 'ProDoctors';
    @endphp

    <title>@yield('title', __('Admin Panel')) - {{ $siteTitle }}</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">

    <style>
        :root {
            --admin-sidebar-width: 250px;
            --admin-header-height: 60px;
            --admin-primary: #0284c7;
        }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0f172a;
            color: #94a3b8;
            overflow-y: auto;
            z-index: 1020;
        }
        .admin-sidebar .nav-link {
            color: #94a3b8;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 2px 10px;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }
        .admin-sidebar .nav-link.active {
            background: var(--admin-primary);
        }
        .admin-main {
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .admin-header {
            height: var(--admin-header-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 1010;
        }
        .admin-content {
            flex: 1;
            padding: 24px;
        }
        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: calc(-1 * var(--admin-sidebar-width));
                transition: margin .25s ease-in-out;
            }
            .admin-sidebar.show {
                margin-left: 0;
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="admin-main">
        @include('admin.partials.header')

        <div class="admin-content">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
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