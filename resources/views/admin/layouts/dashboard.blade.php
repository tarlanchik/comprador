<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Панель управления')</title>

    <!-- XXXXX AdminLTE 3.2 + Bootstrap 4.6 + FontAwesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    @livewireStyles
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    @include('admin.partials.navbar')
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-sm text-center">
        <strong>&copy; {{ date('Y') }} Tarlan.Net.</strong> Все права защищены.
    </footer>
</div>

<!-- Scripts -->
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

@livewireScripts
</body>
</html>
