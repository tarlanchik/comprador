<div>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Comprador Gaming</h1>
            <p class="hero-subtitle">Professional Gaming Peripherals & Computer Accessories</p>

            <!-- Search Bar -->
            <div class="search-bar">
                <form wire:submit.prevent="search">
                    <div class="input-group">
                        <input
                            type="text"
                            wire:model.defer="searchQuery"
                            class="form-control search-input"
                            placeholder="Search for gaming gear..."
                        >
                        <button type="submit" class="btn search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    @if($categories && count($categories) > 0)
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-4" style="color: var(--primary-color);">Shop by Category</h2>

                <div class="category-nav">
                    <div class="row g-3">
                        @foreach($categories as $category)
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ route('products.category', ['lang' => app()->getLocale(), 'categoryId' => $category->id]) }}" class="category-link">
                                    <div class="text-center">
                                        <i class="bi bi-laptop" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                        <div>{{ $category->{'name_' . app()->getLocale()} }}</div>
                                        @if($category->children->count() > 0)
                                            <small class="text-muted">({{ $category->children->count() }} subcategories)</small>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Products Section -->
    @if($featuredProducts && count($featuredProducts) > 0)
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-5" style="color: var(--primary-color);">Featured Products</h2>

                <div class="row g-4">
                    @foreach($featuredProducts as $product)
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="product-card">
                                @if($product->images->count() > 0)
                                    <img
                                        src="{{ asset($product->images->first()->image_path) }}"
                                        alt="{{ $product->{'name_' . app()->getLocale()} }}"
                                        class="product-image"
                                    >
                                @else
                                    <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #666;"></i>
                                    </div>
                                @endif

                                <h5 class="product-title">
                                    {{ $product->{'name_' . app()->getLocale()} }}
                                </h5>

                                <div class="product-price">
                                    @if($product->old_price)
                                        <span class="product-old-price">{{ number_format($product->old_price, 2) }} ₼</span>
                                    @endif
                                    {{ number_format($product->price, 2) }} ₼
                                </div>

                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('products.show', ['lang' => app()->getLocale(), $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <button
                                        wire:click="$dispatch('add-to-cart', { productId: {{ $product->id }} })"
                                        class="btn btn-primary"
                                        @if($product->count <= 0) disabled @endif
                                    >
                                        <i class="bi bi-cart-plus"></i>
                                        @if($product->count > 0)
                                            Add to Cart
                                        @else
                                            Out of Stock
                                        @endif
                                    </button>
                                </div>

                                @if($product->count > 0)
                                    <small class="text-success mt-2 d-block">
                                        <i class="bi bi-check-circle"></i> In Stock ({{ $product->count }})
                                    </small>
                                @else
                                    <small class="text-danger mt-2 d-block">
                                        <i class="bi bi-x-circle"></i> Out of Stock
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                        View All Products <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Latest News Section -->
    @if($latestNews && count($latestNews) > 0)
        <section class="py-5" style="background: rgba(255,255,255,0.02);">
            <div class="container">
                <h2 class="text-center mb-5" style="color: var(--primary-color);">Latest News</h2>

                <div class="row g-4">
                    @foreach($latestNews as $news)
                        <div class="col-md-4">
                            <div class="product-card">
                                @if($news->images->count() > 0)
                                    <img
                                        src="{{ asset($news->images->first()->image_path) }}"
                                        alt="{{ $news->{'title_' . app()->getLocale()} }}"
                                        class="product-image"
                                    >
                                @endif

                                <h5 class="product-title">
                                    {{ Str::limit($news->{'title_' . app()->getLocale()}, 60) }}
                                </h5>

                                <p class="text-muted mb-3">
                                    {{ Str::limit(strip_tags($news->{'content_' . app()->getLocale()}), 100) }}
                                </p>

                                <small class="text-muted d-block mb-3">
                                    <i class="bi bi-calendar"></i> {{ $news->created_at->format('M d, Y') }}
                                </small>

                                <a href="{{ route('news.show', ['lang' => app()->getLocale(), 'news' => $news->id]) }}" class="btn btn-outline-primary">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary">
                        View All News <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="product-card">
                        <i class="bi bi-truck" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                        <h5>Free Shipping</h5>
                        <p class="text-muted">Free delivery on orders over 50 ₼</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="product-card">
                        <i class="bi bi-shield-check" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                        <h5>Warranty</h5>
                        <p class="text-muted">Official warranty on all products</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="product-card">
                        <i class="bi bi-headset" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                        <h5>24/7 Support</h5>
                        <p class="text-muted">Professional customer support</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="product-card">
                        <i class="bi bi-arrow-repeat" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                        <h5>Easy Returns</h5>
                        <p class="text-muted">30-day return policy</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        // Add to cart functionality
        window.addEventListener('add-to-cart', event => {
            // This will be implemented with the cart system
            console.log('Adding product to cart:', event.detail.productId);

            // Show success message
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>Product added to cart!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Update cart count (placeholder)
            updateCartCount();
        });

        function updateCartCount() {
            // This will be implemented with actual cart functionality
            const currentCount = parseInt(document.getElementById('cart-count').textContent) || 0;
            document.getElementById('cart-count').textContent = currentCount + 1;
            document.getElementById('mobile-cart-count').textContent = currentCount + 1;
        }
    </script>
@endpush
