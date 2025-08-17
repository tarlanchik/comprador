<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    {!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Hreflang Links for SEO -->
    @if(isset($hreflangs))
        @foreach($hreflangs as $locale => $url)
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ $hreflangs['en'] ?? url()->current() }}">
    @endif

    <!-- Preload Critical Resources -->
    <link rel="preload" href="{{ asset('css/autoptimize.css') }}" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style">

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    <!-- Stylesheets -->
    <link type="text/css" media="all" href="{{ asset('css/autoptimize.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    @livewireStyles
    @stack('styles')

    <!-- JSON-LD Structured Data -->
    @stack('structured-data')

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
            color: #<div>
            @section('title', __('Shopping Cart'))
            @section('meta_description', __('Review your gaming gear selection and proceed to checkout'))

<div class="container py-5">
            <!-- Page Header -->
            <div class="hero-section mb-5">
            <div class="container">
            <h1 class="hero-title">
            <i class="bi bi-cart3"></i> {{ __('Shopping Cart') }}
                </h1>
            <p class="hero-subtitle">{{ __('Review your gaming gear and proceed to checkout') }}</p>
            </div>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session()->has('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (empty($cartItems))
<!-- Empty Cart -->
            <div class="text-center py-5">
            <div class="empty-cart-icon mb-4">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #666;"></i>
            </div>
            <h3 class="mb-3 text-light">{{ __('Your cart is empty') }}</h3>
            <p class="text-muted mb-4">{{ __('Looks like you haven\'t added any gaming gear to your cart yet.') }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>
            {{ __('Continue Shopping') }}
</a>
            </div>
            @else
<div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
            <div class="cart-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-light mb-0">
            <i class="bi bi-bag me-2"></i>
            {{ __('Cart Items') }} ({{ count($cartItems) }})
        </h4>
        <button wire:click="clearCart" class="btn btn-outline-danger btn-sm"
        onclick="return confirm('{{ __('Are you sure you want to clear the cart?') }}')">
        <i class="bi bi-trash me-1"></i>
        {{ __('Clear Cart') }}
</button>
        </div>

        @foreach ($cartItems as $index => $item)
<div class="cart-item mb-3" wire:key="cart-item-{{ $item['product']->id }}">
        <div class="row align-items-center">
        <!-- Product Image -->
        <div class="col-md-2 col-3">
        <div class="cart-item-image">
        @if($item['product']->images && $item['product']->images->count() > 0)
<img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
        alt="{{ $item['product']->{'name_' . app()->getLocale()} }}"
        class="img-fluid rounded">
        @else
<div class="placeholder-image">
        <i class="bi bi-image"></i>
        </div>
        @endif
</div>
        </div>

        <!-- Product Details -->
        <div class="col-md-4 col-9">
        <div class="cart-item-details">
        <h6 class="cart-item-title mb-2">
        {{ $item['product']->{'name_' . app()->getLocale()} }}
</h6>
        <p class="cart-item-category text-muted mb-1">
        <i class="bi bi-tag me-1"></i>
        {{ $item['product']->category->{'name_' . app()->getLocale()} ?? 'N/A' }}
</p>
        <div class="cart-item-stock">
        @if($item['product']->count > 0)
<span class="badge bg-success">
        <i class="bi bi-check-circle me-1"></i>
        {{ __('In Stock') }} ({{ $item['product']->count }})
        </span>
        @else
<span class="badge bg-danger">
        <i class="bi bi-x-circle me-1"></i>
        {{ __('Out of Stock') }}
</span>
        @endif
</div>
        </div>
        </div>

        <!-- Quantity Controls -->
        <div class="col-md-3 col-6 mt-2 mt-md-0">
        <div class="cart-quantity-controls">
        <label class="form-label text-light small">{{ __('Quantity') }}:</label>
        <div class="input-group">
        <button class="btn btn-outline-secondary btn-sm" type="button"
        wire:click="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})">
        <i class="bi bi-dash"></i>
        </button>
        <input type="number" class="form-control text-center quantity-input"
        value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->count }}"
        wire:change="updateQuantity({{ $item['product']->id }}, $event.target.value)">
        <button class="btn btn-outline-secondary btn-sm" type="button"
        wire:click="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})"
        @if($item['quantity'] >= $item['product']->count) disabled @endif>
        <i class="bi bi-plus"></i>
        </button>
        </div>
        </div>
        </div>

        <!-- Price & Actions -->
        <div class="col-md-3 col-6 mt-2 mt-md-0">
        <div class="cart-item-price text-end">
        <div class="price-per-unit text-muted small mb-1">
        {{ number_format($item['product']->price, 2) }} ₼ {{ __('each') }}
                                            </div>
        <div class="subtotal-price text-primary fw-bold mb-2">
        {{ number_format($item['subtotal'], 2) }} ₼
        </div>
        <button wire:click="removeFromCart({{ $item['product']->id }})"
        class="btn btn-outline-danger btn-sm"
        onclick="return confirm('{{ __('Remove this item from cart?') }}')">
        <i class="bi bi-trash"></i>
        </button>
        </div>
        </div>
        </div>
        </div>
        @endforeach
</div>
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
        <div class="cart-summary-section">
        <div class="cart-summary">
        <h5 class="text-light mb-4">
        <i class="bi bi-receipt me-2"></i>
        {{ __('Order Summary') }}
</h5>

        <div class="summary-row">
        <span>{{ __('Subtotal') }}:</span>
        <span class="fw-bold">{{ number_format($total, 2) }} ₼</span>
        </div>

        <div class="summary-row">
        <span>{{ __('Shipping') }}:</span>
        <span class="text-success">{{ __('Free') }}</span>
        </div>

        <div class="summary-row">
        <span>{{ __('Tax') }}:</span>
        <span>{{ number_format($total * 0.18, 2) }} ₼</span>
        </div>

        <hr class="summary-divider">

        <div class="summary-row total-row">
        <span class="fw-bold">{{ __('Total') }}:</span>
        <span class="fw-bold text-primary fs-4">{{ number_format($total * 1.18, 2) }} ₼</span>
        </div>

        <div class="checkout-actions mt-4">
        <button class="btn btn-primary btn-lg w-100 mb-3 checkout-btn">
        <i class="bi bi-credit-card me-2"></i>
        {{ __('Proceed to Checkout') }}
</button>

        <a href="{{ route('home') }}" class="btn btn-outline-light w-100">
        <i class="bi bi-arrow-left me-2"></i>
        {{ __('Continue Shopping') }}
</a>
        </div>

        <!-- Trust Badges -->
        <div class="trust-badges mt-4 text-center">
        <div class="trust-badge">
        <i class="bi bi-shield-check text-success me-2"></i>
        <small class="text-muted">{{ __('Secure Checkout') }}</small>
        </div>
        <div class="trust-badge mt-2">
        <i class="bi bi-truck text-primary me-2"></i>
        <small class="text-muted">{{ __('Free Shipping') }}</small>
        </div>
        <div class="trust-badge mt-2">
        <i class="bi bi-arrow-return-left text-info me-2"></i>
        <small class="text-muted">{{ __('30-Day Returns') }}</small>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        @endif
    </div>

        <!-- Custom Styles -->
        <style>
        .cart-section {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
        }

            .cart-item {
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 10px;
                padding: 20px;
                transition: all 0.3s ease;
            }

            .cart-item:hover {
                background: rgba(255,255,255,0.08);
                border-color: var(--primary-color);
            }

            .cart-item-image img {
                width: 80px;
                height: 80px;
                object-fit: cover;
                border-radius: 8px;
            }

            .placeholder-image {
                width: 80px;
                height: 80px;
                background: rgba(255,255,255,0.1);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #666;
                font-size: 24px;
            }

            .cart-item-title {
                color: #fff;
                font-weight: 600;
            }

            .cart-item-category {
                font-size: 0.9em;
            }

            .quantity-input {
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                color: #fff;
                width: 80px;
            }

            .quantity-input:focus {
                background: rgba(255,255,255,0.15);
                border-color: var(--primary-color);
                color: #fff;
                box-shadow: 0 0 0 0.2rem rgba(41, 133, 220, 0.25);
            }

            .cart-summary-section {
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 15px;
                padding: 30px;
                position: sticky;
                top: 20px;
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                color: #fff;
            }

            .summary-divider {
                border-color: rgba(255,255,255,0.2);
                margin: 20px 0;
            }

            .total-row {
                font-size: 1.1em;
                padding: 20px 0;
                border-top: 2px solid var(--primary-color);
            }

            .checkout-btn {
                background: var(--primary-color);
                border: none;
                border-radius: 10px;
                padding: 15px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .checkout-btn:hover {
                background: #1e6bb8;
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(41, 133, 220, 0.3);
            }

            .trust-badge {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .empty-cart-icon {
                opacity: 0.5;
            }

            @media (max-width: 768px) {
                .cart-section, .cart-summary-section {
                    padding: 20px;
                }

                .cart-item {
                    padding: 15px;
                }

                .cart-item-image img, .placeholder-image {
                    width: 60px;
                    height: 60px;
                }

                .cart-item-title {
                    font-size: 14px;
                }

                .quantity-input {
                    width: 60px;
                }
            }
    </style>
    </div>
