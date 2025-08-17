<div>
    <!-- Page Header -->
    <section class="py-4" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid #333;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0" style="color: var(--primary-color);">
                        <i class="bi bi-newspaper"></i> Latest News
                    </h1>
                    <p class="text-muted mb-0 mt-2">Stay updated with the latest gaming trends and product releases</p>
                </div>

                <div class="col-md-6">
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <div class="input-group">
                            <input type="text" wire:model.defer="searchQuery" class="form-control search-input" placeholder="Search news...">
                            <button wire:click="$refresh" class="btn search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        @if($newsItems->count() > 0)
            <div class="row g-4">
                @foreach($newsItems as $news)
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card h-100">
                            @if($news->images->count() > 0)
                                <img
                                    src="{{ asset($news->images->first()->image_path) }}"
                                    alt="{{ $news->{'title_' . app()->getLocale()} }}"
                                    class="product-image"
                                >
                            @endif

                            <div class="p-3">
                                <h5 class="product-title">
                                    {{ $news->{'title_' . app()->getLocale()} }}
                                </h5>

                                <p class="text-muted mb-3">
                                    {{ Str::limit(strip_tags($news->{'content_' . app()->getLocale()}), 120) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $news->created_at->format('M d, Y') }}
                                    </small>
                                    <a href="{{ route('news.show', $news->id) }}" class="btn btn-outline-primary btn-sm">
                                        Read More <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $newsItems->links('pagination::bootstrap-5') }}
            </div>
        @else
            <!-- No News Found -->
            <div class="text-center py-5">
                <div class="product-card p-5 mx-auto" style="max-width: 500px;">
                    <i class="bi bi-newspaper" style="font-size: 5rem; color: #666; margin-bottom: 20px;"></i>
                    <h3 class="text-muted mb-3">No News Found</h3>
                    @if($searchQuery)
                        <p class="text-muted">No news articles match your search for "{{ $searchQuery }}"</p>
                        <button wire:click="$set('searchQuery', '')" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-clockwise"></i> Clear Search
                        </button>
                    @else
                        <p class="text-muted">No news articles available at the moment.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
