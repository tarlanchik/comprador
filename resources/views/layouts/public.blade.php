<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <title>@yield('title', 'Comprador Gaming - Computer Shop')</title>
    <meta name="description" content="@yield('description', 'Professional gaming peripherals and computer accessories')">
    <meta name="keywords" content="@yield('keywords', 'gaming, mouse, keyboard, audio, accessories')">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @livewireStyles
    @stack('styles')

    <style>
        :root {
            --primary-color: #2985DC;
            --dark-bg: #1a1a1a;
            --light-gray: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
            color: #fff;
            overflow-x: hidden;
        }

        /* Header Styles */
        #header-container {
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            padding: 20px 0;
            border-bottom: 2px solid #333;
        }

        #header-logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        #header-logo {
            max-height: 80px;
            width: auto;
        }

        #header-menu {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .header-element {
            position: relative;
            padding: 15px 20px;
            margin: 5px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            text-decoration: none;
            color: #fff;
            transition: all 0.3s ease;
            display: block;
            text-align: center;
            min-width: 120px;
        }

        .header-element:hover {
            background: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(41, 133, 220, 0.3);
            text-decoration: none;
        }

        .header-element-text {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            background: #000;
            padding: 15px;
            border-bottom: 1px solid #333;
            position: relative;
        }

        .mobile-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-menu-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            padding: 5px;
        }

        .mobile-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #1a1a1a;
            border-top: 1px solid #333;
            display: none;
            z-index: 1000;
        }

        .mobile-menu.show {
            display: block;
        }

        .mobile-menu a {
            display: block;
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid #333;
            transition: background 0.3s ease;
        }

        .mobile-menu a:hover {
            background: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 300px);
            padding: 40px 0;
        }

        /* Product Grid */
        .product-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            color: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(41, 133, 220, 0.2);
            border-color: var(--primary-color);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 10px;
            color: #fff;
        }

        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .product-old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 18px;
            margin-right: 10px;
        }

        /* Language Switcher */
        .language-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        .language-switcher select {
            background: rgba(0,0,0,0.8);
            color: #fff;
            border: 1px solid #333;
            border-radius: 5px;
            padding: 5px 10px;
        }

        /* Footer */
        #footer-container {
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            border-top: 2px solid #333;
            padding: 40px 0 20px;
            color: #fff;
        }

        .footer-section {
            margin-bottom: 30px;
        }

        .footer-title {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .footer-link {
            color: #ccc;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #header-container {
                display: none;
            }

            .mobile-header {
                display: block;
            }

            .main-content {
                padding: 20px 0;
            }

            .header-element {
                min-width: 100px;
                padding: 10px 15px;
            }
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            padding: 60px 0;
            text-align: center;
            border-bottom: 1px solid #333;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #ccc;
            margin-bottom: 30px;
        }

        /* Search Bar */
        .search-bar {
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .search-input {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 25px;
            padding: 12px 20px;
        }
        .text-muted{
            color: var(--gray-dark) !important;
        }

        .search-input::placeholder {
            color: #ccc;
        }

        .search-btn {
            border-radius: 25px;
            background: var(--primary-color);
            border: none;
            padding: 12px 25px;
        }

        /* Category Navigation */
        .category-nav {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .category-link {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 5px;
        }

        .category-link:hover, .category-link.active {
            background: var(--primary-color);
            color: #fff;
        }
    </style>
</head>

<body>
<!-- Language Switcher -->
<div class="language-switcher">
    <livewire:public.languageswitcher />
</div>

<!-- Desktop Header -->
<div id="header-container">
    <div class="container">
        <div id="header-logo-container">
            <a href="{{ route('home', ['lang' => app()->getLocale()]) }}">
                <img id="header-logo" src="{{ asset('img/logo.jpg') }}" alt="Comprador shop">
            </a>
        </div>

        <div id="header-menu">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <div class="d-flex flex-wrap justify-content-center">
                        @foreach($categories ?? [] as $category)
                            <a href="{{ route('products.category', ['lang' => app()->getLocale(), 'categoryId' => $category->id]) }}" class="header-element">
                                <div class="header-element-text">{{ $category->{'name_' . app()->getLocale()} }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Secondary Navigation -->
            <div class="row justify-content-center mt-3">
                <div class="col-auto">
                    <div class="d-flex flex-wrap justify-content-center">
                        <a href="{{ route('about', ['lang' => app()->getLocale()]) }}" class="header-element">
                            <div class="header-element-text">About</div>
                        </a>
                        <a href="{{ route('news.index', ['lang' => app()->getLocale()]) }}" class="header-element">
                            <div class="header-element-text">News</div>
                        </a>
                        <a href="{{ route('contact', ['lang' => app()->getLocale()]) }}" class="header-element">
                            <div class="header-element-text">Contact</div>
                        </a>
                        <a href="{{ route('cart', ['lang' => app()->getLocale()]) }}" class="header-element">
                            <div class="header-element-text">
                                <i class="bi bi-cart"></i> Cart
                                <span class="badge bg-primary ms-1" id="cart-count">0</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Header -->
<div class="mobile-header">
    <div class="mobile-header-content">
        <a href="{{ route('home', ['lang' => app()->getLocale()]) }}">
            <img src="{{ asset('/img/logo.jpg') }}" alt="Comprador shop" style="height: 40px;">
        </a>
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="mobile-menu" id="mobileMenu">
        @foreach($categories ?? [] as $category)
            <a href="{{ route('products.category', ['lang' => app()->getLocale(), 'categoryId' => $category->id])}}">{{ $category->{'name_' . app()->getLocale()} }}</a>
        @endforeach
        <hr style="border-color: #333; margin: 10px 0;">
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('news.index') }}">News</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('cart') }}">
            <i class="bi bi-cart"></i> Cart (<span id="mobile-cart-count">0</span>)
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
      {{ $slot ?? '' }}
</div>

<!-- Footer -->
<div id="footer-container">
    <div class="container">
        <div class="row">
            <div class="col-md-2 footer-section">
                <div class="footer-title">Products</div>
                @foreach($categories ?? [] as $category)
                    @if($loop->index < 5)
                        <a href="{{ route('products.category', ['lang' => app()->getLocale(), 'categoryId' => $category->id]) }}" class="footer-link">
                            {{ $category->{'name_' . app()->getLocale()} }}
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="col-md-2 footer-section">
                <div class="footer-title">About</div>
                <a href="{{ route('about') }}" class="footer-link">Company</a>
                <a href="#" class="footer-link">History</a>
                <a href="#" class="footer-link">Team</a>
            </div>

            <div class="col-md-2 footer-section">
                <div class="footer-title">Support</div>
                <a href="{{ route('contact') }}" class="footer-link">Contact</a>
                <a href="#" class="footer-link">FAQ</a>
                <a href="#" class="footer-link">Support</a>
            </div>

            <div class="col-md-2 footer-section">
                <div class="footer-title">News</div>
                <a href="{{ route('news.index') }}" class="footer-link">Events</a>
                <a href="#" class="footer-link">Reviews</a>
                <a href="#" class="footer-link">Updates</a>
            </div>

            <div class="col-md-2 footer-section">
                <div class="footer-title">Download</div>
                <a href="#" class="footer-link">Software</a>
                <a href="#" class="footer-link">Drivers</a>
                <a href="#" class="footer-link">Manuals</a>
            </div>

            <div class="col-md-2 footer-section">
                <div class="footer-title">Community</div>
                <a href="#" class="footer-link" target="_blank">
                    <i class="bi bi-instagram"></i> Instagram
                </a>
                <a href="#" class="footer-link" target="_blank">
                    <i class="bi bi-facebook"></i> Facebook
                </a>
                <a href="#" class="footer-link" target="_blank">
                    <i class="bi bi-youtube"></i> YouTube
                </a>
            </div>
        </div>

        <hr style="border-color: #333; margin: 30px 0 20px;">

        <div class="text-center">
            <p style="margin: 0; color: #ccc;">
                Copyright © {{ date('Y') }} Comprador Gaming. All rights reserved.
            </p>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary" style="position: fixed; bottom: 20px; right: 20px; border-radius: 50%; width: 50px; height: 50px; display: none; z-index: 1000;">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
@stack('scripts')

<script>
    // Mobile menu toggle
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('show');
    }

    // Back to top functionality
    window.addEventListener('scroll', function() {
        const backToTop = document.getElementById('backToTop');
        if (window.pageYOffset > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    document.getElementById('backToTop').addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        const btn = document.querySelector('.mobile-menu-btn');

        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.remove('show');
        }
    });
</script>
</body>
</html>
