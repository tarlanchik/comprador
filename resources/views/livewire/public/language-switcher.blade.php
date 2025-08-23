<div class="language-switcher-dropdown">
    <div class="dropdown">
        <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center"
                type="button"
                id="languageDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <span class="flag-emoji me-2">{{ $supportedLocales[$current]['flag'] }}</span>
            <span class="locale-name d-none d-md-inline">{{ $supportedLocales[$current]['name'] }}</span>
            <span class="locale-code d-md-none">{{ strtoupper($current) }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="languageDropdown">
            @foreach(config('app.supported_locales') as $localeCode => $localeData)
                <li>
                    <a class="dropdown-item d-flex align-items-center {{ $localeCode === $current ? 'active' : '' }}" href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['lang' => $localeCode])) }}">
                        <span class="flag-emoji me-2">{{ $localeData['flag'] }}</span>
                        <span class="locale-name">{{ $localeData['name'] }}</span>
                        @if($localeCode === $current)
                            <i class="bi bi-check ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>


<style>
    .language-switcher-dropdown {
        z-index: 1050;
    }

    .language-switcher-dropdown .btn {
        background: rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        min-width: 70px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .language-switcher-dropdown .btn:hover,
    .language-switcher-dropdown .btn:focus {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(41, 133, 220, 0.3);
    }

    .language-switcher-dropdown .dropdown-menu {
        background: rgba(0,0,0,0.9);
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(15px);
        border-radius: 8px;
        min-width: 150px;
    }

    .language-switcher-dropdown .dropdown-item {
        color: #fff;
        padding: 10px 15px;
        border: none;
        background: transparent;
        transition: all 0.3s ease;
    }

    .language-switcher-dropdown .dropdown-item:hover {
        background: var(--primary-color);
        color: #fff;
    }

    .language-switcher-dropdown .dropdown-item.active {
        background: rgba(41, 133, 220, 0.2);
        color: var(--primary-color);
    }

    .language-switcher-dropdown .flag-emoji {
        font-size: 1.1em;
        display: inline-block;
        width: 20px;
    }

    .language-switcher-dropdown .locale-name {
        font-size: 14px;
        font-weight: 500;
    }

    .language-switcher-dropdown .locale-code {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .language-switcher-dropdown .dropdown-menu {
            min-width: 120px;
        }

        .language-switcher-dropdown .dropdown-item {
            padding: 8px 12px;
            font-size: 13px;
        }
    }

    /* Loading state */
    .language-switcher-dropdown [wire\:loading] .btn {
        opacity: 0.7;
        pointer-events: none;
    }

    .language-switcher-dropdown [wire\:loading] .dropdown-item {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

@if (session()->has('locale_changed'))
    <script>
        // Optional: Show a subtle notification
        setTimeout(() => {
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 m-3 alert alert-success alert-dismissible fade show';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                {{ session('locale_changed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
`;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }, 100);
    </script>
@endif
</div>
