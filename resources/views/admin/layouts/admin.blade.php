<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель управления</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/sortable/Sortable.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini">
@if (Route::currentRouteName() == 'admin.admin-categories' || Route::currentRouteName() == 'admin.admin-product-types')
<script>
    window.addEventListener('open-edit-category-modal', event => {
        const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    });

    window.addEventListener('close-edit-category-modal', event => {
        const modalEl = document.getElementById('editCategoryModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });

    window.addEventListener('open-edit-type-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('editTypeModal'));
        modal.show();
    });

    window.addEventListener('close-edit-type-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editTypeModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('open-edit-parameter-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('editParameterModal'));
        modal.show();
    });

    window.addEventListener('close-edit-parameter-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editParameterModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('open-parameters-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('parametersModal'));
        modal.show();
    });

    window.addEventListener('close-parameters-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('parametersModal'));
        if (modal) modal.hide();
    });
</script>
@endif
<div class="wrapper">
    <!-- Navbar -->
    @include('admin.partials.navbar')

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
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@livewireScripts
</body>
</html>
