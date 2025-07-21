<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light text-bold">Comprador</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item ">
                    <a href="{{ route('admin.admin-dashboard') }}" class="nav-link {{ (Route::currentRouteName() == 'admin.admin-dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Главная</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.admin-categories') }}" class="nav-link {{ (Route::currentRouteName() == 'admin.admin-categories') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Категории</p>
                    </a>
                </li>
               <li class="nav-item has-treeview menu-open">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Товары</p>
                    </a>

                   <ul class="nav nav-treeview">
                       <li class="nav-item">
                           <a href="{{ route('admin.goods-index') }}" class="nav-link">
                               <i class="far fa-circle nav-icon"></i>
                               <p>Список товаров</p>
                           </a>
                       </li>
                       <li class="nav-item">
                           <a href="{{ route('admin.goods-create') }}" class="nav-link">
                               <i class="far fa-circle nav-icon"></i>
                               <p>Добавить товар</p>
                           </a>
                       </li>
                   </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.admin-product-types') }}" class="nav-link {{ (Route::currentRouteName() == 'admin.admin-product-types') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Шаблоны</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
