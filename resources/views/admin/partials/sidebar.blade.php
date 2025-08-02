<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="Administrator" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Админ панель </span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item">
                    <a href="{{ route('admin.admin-dashboard') }}" class="nav-link {{ request()->routeIs('admin.admin-dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-browser-edge"></i>
                        <p>Главная</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.admin-categories') }}" class="nav-link {{ request()->routeIs('admin.admin-categories') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-ui-checks-grid"></i>
                        <p>Категории</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.admin-product-types') }}" class="nav-link {{ request()->routeIs('admin.admin-product-types') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-star-half"></i>
                        <p>Шаблоны</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.goods-index') ? 'menu-open' : '' }} {{ request()->routeIs('admin.goods-create') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.goods-index') ? 'active' : '' }} {{ request()->routeIs('admin.goods-create') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Товары <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.goods-index') }}" class="nav-link {{ request()->routeIs('admin.goods-index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle text-warning"></i>
                                <p>Список товаров</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.goods-create') }}" class="nav-link {{ request()->routeIs('admin.goods-create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle text-info"></i>
                                <p>Добавить товар</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.goods-index') ? 'menu-open' : '' }} {{ request()->routeIs('admin.goods-create') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.goods-index') ? 'active' : '' }} {{ request()->routeIs('admin.goods-create') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>Новости <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.goods-index') }}" class="nav-link {{ request()->routeIs('admin.goods-index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle text-warning"></i>
                                <p>Список новостей</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.goods-create') }}" class="nav-link {{ request()->routeIs('admin.goods-create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle text-info"></i>
                                <p>Добавить новость</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>
