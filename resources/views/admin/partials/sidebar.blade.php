<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light text-bold">Comprador</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.admin-dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.admin-dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Главная</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.admin-categories') }}"
                       class="nav-link {{ request()->routeIs('admin.admin-categories') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Категории</p>
                    </a>
                </li>

                @php
                    $goodsRoutes = ['admin.goods-index', 'admin.goods-create'];
                    $isGoodsOpen = request()->routeIs(...$goodsRoutes);
                @endphp
                <li class="nav-item has-treeview {{ $isGoodsOpen ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isGoodsOpen ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Товары
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="{{ $isGoodsOpen ? 'display: block;' : '' }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.goods-index') }}"
                               class="nav-link {{ request()->routeIs('admin.goods-index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Список товаров</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.goods-create') }}"
                               class="nav-link {{ request()->routeIs('admin.goods-create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Добавить товар</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.admin-product-types') }}"
                       class="nav-link {{ request()->routeIs('admin.admin-product-types') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Шаблоны</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
