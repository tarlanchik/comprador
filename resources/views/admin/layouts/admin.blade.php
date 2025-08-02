<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Панель управления</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="Tarlan.Net" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" />
    <script src="{{ asset('vendor/sortable/Sortable.min.js') }}"></script>
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('vendor/adminlte/dist/css/adminlte.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    @livewireStyles
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<div class="app-wrapper">
    @include('admin.partials.navbar')
    @include('admin.partials.sidebar')

    <main class="app-main">
        <div class="content-wrapper">
            <section class="content pt-3">
                <div class="container-fluid">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </section>
        </div>
    </main>
    @include('admin.partials.footer')
</div>
<script src="{{ asset('vendor/adminlte/dist/js/popper.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('vendor/adminlte/dist/js/bootstrap.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!--<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>-->
@livewireScripts
@stack('scripts')

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
</body>
</html>
