<div>
    <!-- Breadcrumb -->
    <section class="py-3" style="background: rgba(255,255,255,0.02);">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index') }}" class="text-primary">Products</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.category', $product->category->id) }}" class="text-primary">
                            {{ $product->category->{'name_' . app()->getLocale()} }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white">
                        {{ $product->{'name_' . app()->getLocale()} }}
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-5">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-card p-4">
                    @if($product->images->count() > 0)
                        <!-- Main Image -->
                        <div class="mb-3">
                            <img
                                src="{{ asset($product->images[$selectedImageIndex]->image_path) }}"
                                alt="{{ $product->{'name_' . app()->getLocale()} }}"
                                class="w-100 rounded"
                                style="height: 400px; object-fit: cover;"
                                id="mainProductImage"
                            >
                        </div>

                        <!-- Thumbnail Images -->
                        @if($product->images->count() > 1)
                            <div class="row g-2">
                                @foreach($product->images as $index => $image)
                                    <div class="col-3">
                                        <img
                                            src="{{ asset($image->image_path) }}"
                                            alt="Image {{ $index + 1 }}"
                                            class="w-100 rounded cursor-pointer {{ $selectedImageIndex == $index ? 'border border-primary border-3' : '' }}"
                                            style="height: 80px; object-fit: cover; cursor: pointer;"
                                            wire:click="selectImage({{ $index }})"
                                        >
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-secondary rounded" style="height: 400px;">
                            <i class="bi bi-image" style="font-size: 5rem; color: #666;"></i>
                        </div>
                    @endif

                    <!-- YouTube Video -->
                    @if($product->youtube_link)
                        <div class="mt-4">
                            <h6 class="text-primary">Product Video</h6>
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="{{ str_replace('watch?v=', 'embed/', $product->youtube_link) }}"
                                    allowfullscreen
                                    class="rounded"
                                ></iframe>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-card p-4 h-100">
                    <!-- Product Title -->
                    <h1 class="product-title mb-3">
                        {{ $product->{'name_' . app()->getLocale()} }}
                    </h1>

                    <!-- Category -->
                    <div class="mb-3">
                        <span class="badge bg-secondary">
                            {{ $product->category->{'name_' . app()->getLocale()} }}
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        @if($product->old_price && $product->old_price > $product->price)
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="product-old-price fs-4">{{ number_format($product->old_price, 2) }} ₼</span>
                                <span class="badge bg-danger">
                                    -{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                </span>
                            </div>
                        @endif
                        <div class="product-price fs-2">{{ number_format($product->price, 2) }} ₼</div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if($product->count > 0)
                            <div class="text-success">
                                <i class="bi bi-check-circle"></i> In Stock
                                @if($product->count <= 10)
                                    <span class="text-warning ms-2">
                                        (Only {{ $product->count }} items left)
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="text-danger">
                                <i class="bi bi-x-circle"></i> Out of Stock
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($product->{'description_' . app()->getLocale()})
                        <div class="mb-4">
                            <h6 class="text-primary">Description</h6>
                            <p class="text-muted">{{ $product->{'description_' . app()->getLocale()} }}</p>
                        </div>
                    @endif

                    <!-- Product Parameters -->
                    @if($product->parameterValues->count() > 0)
                        <div class="mb-4">
                            <h6 class="text-primary">Specifications</h6>
                            <div class="table-responsive">
                                <table class="table table-dark table-sm">
                                    @foreach($product->parameterValues as $paramValue)
                                        @if($paramValue->value)
                                            <tr>
                                                <td class="fw-semibold">
                                                    {{ $paramValue->parameter->{'name_' . app()->getLocale()} }}:
                                                </td>
                                                <td>{{ $paramValue->value }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Add to Cart Section -->
                    @if($product->count > 0)
                        <div class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label class="form-label mb-0">Quantity:</label>
                                </div>
                                <div class="col-auto">
                                    <div class="input-group" style="width: 120px;">
                                        <button
                                            wire:click="decrementQuantity"
                                            class="btn btn-outline-secondary"
                                            type="button"
                                        >-</button>
                                        <input
                                            type="number"
                                            wire:model="quantity"
                                            class="form-control text-center search-input"
                                            min="1"
                                            max="{{ $product->count }}"
                                        >
                                        <button
                                            wire:click="incrementQuantity"
                                            class="btn btn-outline-secondary"
                                            type="button"
                                        >+</button>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button
                                        wire:click="addToCart"
                                        class="btn btn-primary btn-lg"
                                    >
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-heart"></i> Add to Wishlist
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-share"></i> Share
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-question-circle"></i> Ask Question
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="product-card p-0">
                    <ul class="nav nav-tabs" id="productTabs">
                        <li class="nav-item">
                            <button class="nav-link active bg-transparent text-white" data-bs-toggle="tab" data-bs-target="#description">
                                Description
                            </button>
                        </li>
                        @if($product->parameterValues->count() > 0)
                            <li class="nav-item">
                                <button class="nav-link bg-transparent text-white" data-bs-toggle="tab" data-bs-target="#specifications">
                                    Specifications
                                </button>
                            </li>
                        @endif
                        <li class="nav-item">
                            <button class="nav-link bg-transparent text-white" data-bs-toggle="tab" data-bs-target="#reviews">
                                Reviews
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="description">
                            <h5 class="text-primary mb-3">Product Description</h5>
                            <p class="text-muted">
                                {{ $product->{'description_' . app()->getLocale()} ?: 'No description available.' }}
                            </p>
                        </div>

                        @if($product->parameterValues->count() > 0)
                            <div class="tab-pane fade" id="specifications">
                                <h5 class="text-primary mb-3">Technical Specifications</h5>
                                <div class="table-responsive">
                                    <table class="table table-dark">
                                        @foreach($product->parameterValues as $paramValue)
                                            @if($paramValue->value)
                                                <tr>
                                                    <td class="fw-semibold" style="width: 40%;">
                                                        {{ $paramValue->parameter->{'name_' . app()->getLocale()} }}
                                                    </td>
                                                    <td>{{ $paramValue->value }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="tab-pane fade" id="reviews">
                            <h5 class="text-primary mb-3">Customer Reviews</h5>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-star" style="font-size: 3rem; margin-bottom: 15px;"></i>
                                <p>No reviews yet. Be the first to review this product!</p>
                                <button class="btn btn-outline-primary">Write a Review</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts && count($relatedProducts) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="text-center mb-4" style="color: var(--primary-color);">Related Products</h3>

                    <div class="row g-4">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="col-sm-6 col-lg-3">
                                <div class="product-card">
                                    @if($relatedProduct->images->count() > 0)
                                        <img
                                            src="{{ asset($relatedProduct->images->first()->image_path) }}"
                                            alt="{{ $relatedProduct->{'name_' . app()->getLocale()} }}"
                                            class="product-image"
                                        >
                                    @else
                                        <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #666;"></i>
                                        </div>
                                    @endif

                                    <h6 class="product-title">
                                        {{ Str::limit($relatedProduct->{'name_' . app()->getLocale()}, 40) }}
                                    </h6>

                                    <div class="product-price fs-5">
                                        {{ number_format($relatedProduct->price, 2) }} ₼
                                    </div>

                                    <div class="d-flex gap-2 justify-content-center mt-3">
                                        <a href="{{ route('products.show', $relatedProduct->id) }}" class="btn btn-outline-primary btn-sm">
                                            View
                                        </a>
                                        <button
                                            wire:click="$dispatch('add-to-cart', { productId: {{ $relatedProduct->id }} })"
                                            class="btn btn-primary btn-sm"
                                            @if($relatedProduct->count <= 0) disabled @endif
                                        >
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
    <style>
        .nav-tabs .nav-link {
            border: 1px solid rgba(255,255,255,0.1);
            border-bottom: none;
        }

        .nav-tabs .nav-link.active {
            background-color: rgba(255,255,255,0.1) !important;
            border-color: rgba(255,255,255,0.2);
            color: var(--primary-color) !important;
        }

        .tab-content {
            border: 1px solid rgba(255,255,255,0.1);
            border-top: none;
            background: rgba(255,255,255,0.02);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #666;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .table-dark {
            background-color: rgba(255,255,255,0.05);
        }

        .table-dark td {
            border-color: rgba(255,255,255,0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Image zoom on hover (optional enhancement)
        document.getElementById('mainProductImage')?.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.3s ease';
        });

        document.getElementById('mainProductImage')?.addEventListener('mouseout', function() {
            this.style.transform = 'scale(1)';
        });
    </script>
@endpush
