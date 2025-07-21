<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.xx.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body>
<div class="min-vh-100 bg-light">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white shadow mb-4">
            <div class="container py-3">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

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
</div>
</body>
</html>
