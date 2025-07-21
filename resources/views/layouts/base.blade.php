<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
{{-- Navigation (optional) --}}
@includeWhen(View::exists('layouts.navigation'), 'layouts.navigation')

{{-- Header --}}
@hasSection('header')
    <header class="bg-white shadow-sm mb-4">
        <div class="container py-3">
            @yield('header')
        </div>
    </header>
@endif

{{-- Page Content --}}
<main class="flex-fill">
    <div class="container">
        @yield('content')
    </div>
</main>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@livewireScripts
@stack('scripts')

{{-- Global Events --}}
<script>
    function setupModalEvents(id) {
        window.addEventListener(`open-${id}-modal`, () => {
            new bootstrap.Modal(document.getElementById(`${id}Modal`)).show();
        });
        window.addEventListener(`close-${id}-modal`, () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById(`${id}Modal`));
            if (modal) modal.hide();
        });
    }

    ['edit-category', 'edit-type', 'edit-parameter', 'parameters'].forEach(setupModalEvents);
</script>
</body>
</html>
