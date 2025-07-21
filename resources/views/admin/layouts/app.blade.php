<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- Navbar --}}
    @include('admin.partials.navbar')

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content pt-4">
            <div class="container-fluid">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('admin.partials.footer')

</div>

<!-- AdminLTE Scripts -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<!-- Livewire Scripts -->
@livewireScripts
</body>
</html>
