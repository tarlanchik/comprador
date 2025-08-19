<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <!-- Можно вставить лого сюда -->
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Левая часть меню -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="{{ route('home') }}"
                       class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        {{ __('home') }}
                    </a>
                </li>
                <li class="nav-item"><a href="/admin/goods" class="nav-link active">Goods</a></li>
                <li class="nav-item"><a href="/admin/product-types" class="nav-link active">Product types</a></li>
                <li class="nav-item"><a href="/admin/categories" class="nav-link active">Categories</a></li>
                <!-- Добавляй другие ссылки сюда -->
            </ul>

            <!-- Правая часть меню (профиль пользователя и выход) -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::check() ? Auth::user()->name : 'Гость' }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
