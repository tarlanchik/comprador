<div>
    <!-- Page Header -->
    <section class="py-4" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid #333;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0" style="color: var(--primary-color);">
                        @if($currentCategory)
                            {{ $currentCategory->{'name_' . app()->getLocale()} }}
                        @elseif($searchQuery)
                            Search Results for "{{ $searchQuery }}"
                        @else
                            All Products
                        @endif
                    </h1>

                    @if($currentCategory && $currentCategory->{'description_' . app()->getLocale()})
                        <p class="text-muted mb-0 mt-2">
                            {{ $currentCategory->{'description_' . app()->getLocale()} }}
                        </p>
                    @endif
                </div>

                <div class="col-md-6">
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <div class="input-group">
                            <input
                                type="text"
                                wire:model.defer="searchQuery"
                                class="form-control search-input"
                                placeholder="Search products..."
                            >
                            <button wire:click="$refresh" class="btn search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="product-card p-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="bi bi-funnel"></i> Filters
                    </h5>

                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3">Categories</h6>
                        <div class="list-group list-group-flush">
                            <a
                                wire:click="$set('categoryId', null)"
                                class="list-group-item list-group-item-action bg-transparent border-0 text-white px-0 {{ !$categoryId ? 'active' : '' }}"
                                style="cursor: pointer;"
                            >
                                All Categories
                            </a>

                            @foreach($categories as $category)
                                <a
                                    wire:click="$set('categoryId', {{ $category->id }})"
                                    class="list-group-item list-group-item-action bg-transparent border-0 text-white px-0 {{ $categoryId == $category->id ? 'active' : '' }}"
                                    style="cursor: pointer;"
                                >
                                    {{ $category->{'name_' . app()->getLocale()} }}
                                </a>

                                @if($category->children->count() > 0)
                                    @foreach($category->children as $child)
                                        <a
                                            wire:click="$set('categoryId', {{ $child->id }})"
                                            class="list-group-item list-group-item-action bg-transparent border-0 text-white px-3 {{ $categoryId == $child->id ? 'active' : '' }}"
                                            style="cursor: pointer;"
                                        >
                                            ↳ {{ $child->{'name_' . app()->getLocale()} }}
                                        </a>

                                        @if($child->children->count() > 0)
                                            @foreach($child->children as $grandchild)
                                                <a
                                                    wire:click="$set('categoryId', {{ $grandchild->id }})"
                                                    class="list-group-item list-group-item-action bg-transparent border-0 text-white px-4 {{ $categoryId == $grandchild->id ? 'active' : '' }}"
                                                    style="cursor: pointer;"
                                                >
                                                    ⮑ {{ $grandchild->{'name_' . app()->getLocale()} }}
                                                </a>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3">Price Range (₼)</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input
                                    type="number"
                                    wire:model.defer="priceMin"
                                    placeholder="Min"
                                    class="form-control form-control-sm search-input"
                                    min="0"
                                    step="0.01"
                                >
                            </div>
                            <div class="col-6">
                                <input
                                    type="number"
                                    wire:model.defer="priceMax"
                                    placeholder="Max"
                                    class="form-control form-control-sm search-input"
                                    min="0"
                                    step="0.01"
                                >
                            </div>
                        </div>
                        <button wire:click="$refresh" class="btn btn-sm btn-outline-primary mt-2 w-100">
                            Apply Price Filter
                        </button>
                    </div>

                    <!-- Clear Filters -->
                    <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Clear All Filters
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Sort and Results Count -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <span class="text-muted">
                            Showing {{ $products->count() }} of {{ $products->total() }} products
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <label class="text-muted mb-0">Sort by:</label>
                        <select wire:model="sortBy" class="form-select form-select-sm search-input" style="width: auto;">
                            <option value="latest">Latest</option>
                            <option value="oldest">Oldest</option>
                            <option value="name">Name A-Z</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-sm-6 col-xl-4">
                                <div class="product-card">
                                    <!-- Product Image -->
                                    @if($product->images->count() > 0)
                                        <img
                                            src="{{ asset($product->images->first()->image_path) }}"
                                            alt="{{ $product->{'name_' . app()->getLocale()} }}"
                                            class="product-image"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #666;"></i>
                                        </div>
                                    @endif

                                    <!-- Product Info -->
                                    <h5 class="product-title">
                                        {{ $product->{'name_' . app()->getLocale()} }}
                                    </h5>

                                    <div class="text-muted mb-2">
                                        <small>{{ $product->category->{'name_' . app()->getLocale()} }}</small>
                                    </div>

                                    <!-- Product Description -->
                                    @if($product->{'description_' . app()->getLocale()})
                                        <p class="text-muted small mb-3">
                                            {{ Str::limit($product->{'description_' . app()->getLocale()}, 80) }}
                                        </p>
                                    @endif

                                    <!-- Price -->
                                    <div class="product-price">
                                        @if($product->old_price && $product->old_price > $product->price)
                                            <span class="product-old-price">{{ number_format($product->old_price, 2) }} ₼</span>
                                            <div class="badge bg-danger mb-2">
                                                Save {{ number_format($product->old_price - $product->price, 2) }} ₼
                                            </div>
                                        @endif
                                        <div>{{ number_format($product->price, 2) }} ₼</div>
                                    </div>

                                    <!-- Stock Status -->
                                    @if($product->count > 0)
                                        <small class="text-success d-block mb-3">
                                            <i class="bi bi-check-circle"></i> In Stock
                                            @if($product->count <= 5)
                                                <span class="text-warning">(Only {{ $product->count }} left)</span>
                                            @endif
                                        </small>
                                    @else
                                        <small class="text-danger d-block mb-3">
                                            <i class="bi bi-x-circle"></i> Out of Stock
                                        </small>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary flex-grow-1">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        <button
                                            wire:click="addToCart({{ $product->id }})"
                                            class="btn btn-primary"
                                            @if($product->count <= 0) disabled @endif
                                        >
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <!-- No Products Found -->
                    <div class="text-center py-5">
                        <div class="product-card p-5">
                            <i class="bi bi-search" style="font-size: 4rem; color: #666; margin-bottom: 20px;"></i>
                            <h4 class="text-muted">No Products Found</h4>
                            <p class="text-muted">
                                @if($searchQuery)
                                    No products match your search for "{{ $searchQuery }}"
                                @else
                                    No products available in this category
                                @endif
                            </p>
                            <button wire:click="clearFilters" class="btn btn-outline-primary mt-3">
                                <i class="bi bi-arrow-clockwise"></i> Clear Filters
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .list-group-item.active {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .pagination .page-link {
            background-color: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.2);
            color: #fff;
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
@endpush
