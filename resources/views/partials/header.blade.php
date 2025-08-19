<header id="header-container">
    <a href="{{ route('home', app()->locales()) }}">
        <div id="header-logo-container">
            <img id="header-logo" src="{{ asset('img/logo.jpg') }}" alt="{{ config('app.name') }}">
        </div>
    </a>

    <div id="header-menu">
        <nav>
            <ul>
                <li><a href="{{ route('catalog.index', app()->locales()) }}">@lang('catalog.all')</a></li>
                <!-- динамически: -->
                @if(!empty($menuCategories) && $menuCategories->count() > 0)
                    @foreach($menuCategories as $cat)
                        <li><a href="{{ route('catalog.category', ['lang' => app()->locales(), 'category' => 'default-category']) }}">{{ $cat->name }}</a></li>
                    @endforeach
                @else
                    <li><a href="{{ route('catalog.category', ['lang' => app()->locales(), 'category' => 'default-category']) }}">Категория по умолчанию</a></li>
                @endif
            </ul>
        </nav>

        <!-- Поисковая форма (в макете есть форма с id headerSearch) -->
        <form action="{{ route('search', app()->locales()) }}" method="get" id="headerSearch">
      <span class="sk">
        <input name="q" id="searchKey" placeholder="Model #" type="text" value="{{ request('q') }}">
      </span>
            <span class="sb">
        <button type="submit">Search</button>
      </span>
        </form>
    </div>
</header>
