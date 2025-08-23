<div>
    <!-- News Detail Header -->
    <div class="news-hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="container position-relative">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="pt-4 pb-3">
                <ol class="breadcrumb custom-breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{route('home', ['lang' => app()->getLocale()])}}" wire:navigate class="text-white-50">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('news.index', ['lang' => app()->getLocale()]) }}" wire:navigate class="text-white-50">News</a>
                    </li>
                    <li class="breadcrumb-item active text-white">{{ $news->category }}</li>
                </ol>
            </nav>

            <!-- Back Button -->
            <a href="{{ route('news.index', ['lang' => app()->getLocale()]) }}" wire:navigate class="btn btn-outline-light rounded-pill mb-4">
                <i class="bi bi-arrow-left"></i> Back to News
            </a>

            <!-- Article Meta Info -->
            <div class="article-meta-header glass-card p-3 rounded-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap align-items-center text-white-75">
                            <span class="me-4">
                                <i class="bi bi-calendar3"></i>
                                {{ $news->created_at->format('M d, Y') }}
                            </span>
                            <span class="me-4">
                                <i class="bi bi-person"></i>
                                {{ $news->author ?? 'Admin' }}
                            </span>
                            <span class="me-4">
                                <i class="bi bi-eye"></i>
                                {{ $news->views ?? 0 }} views
                            </span>

                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        @if($news->tags)
                            @foreach(explode(',', $news->tags) as $tag)
                                <span class="badge bg-primary-gradient rounded-pill me-1">{{ trim($tag) }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Article Title -->
            <h1 class="display-4 fw-bold text-white mb-3 article-title">{{ $news->title }}</h1>

            @if($news->summary)
                <p class="lead text-white-75 mb-4">{{ $news->summary }}</p>
            @endif
        </div>
    </div>

    <!-- Image Carousel Section -->
    @if($news->images && count(json_decode($news->images, true)) > 0)
        <div class="container my-5">
            <div id="newsImageCarousel" class="carousel slide news-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-indicators custom-indicators">
                    @foreach(json_decode($news->images, true) as $index => $image)
                        <button type="button"
                                data-bs-target="#newsImageCarousel"
                                data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $index + 1 }}">
                        </button>
                    @endforeach
                </div>

                <div class="carousel-inner rounded-4 overflow-hidden">
                    @foreach(json_decode($news->images, true) as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset($image['image_path']) }}"
                                 class="d-block w-100 carousel-image"
                                 alt="{{ $image['alt'] ?? 'News Image ' . ($index + 1) }}"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            @if(isset($image['caption']))
                                <div class="carousel-caption">
                                    <div class="caption-content">
                                        <p class="mb-0">{{ $image['caption'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev custom-control" type="button" data-bs-target="#newsImageCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next custom-control" type="button" data-bs-target="#newsImageCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Article Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="article-content-card">
                    <div class="article-body">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </article>

                <!-- YouTube Video Section -->
                @if($news->youtube_url)
                    <div class="video-section mt-5">
                        <div class="video-card">
                            <div class="video-header d-flex align-items-center mb-3">
                                <div class="video-icon me-3">
                                    <i class="bi bi-play-circle-fill text-danger"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">Watch Related Video</h4>
                                    <p class="text-muted mb-0">Additional coverage and insights</p>
                                </div>
                            </div>
                            <div class="video-wrapper">
                                <iframe
                                    src="{{ $this->getYouTubeEmbedUrl() }}"
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Article Actions -->
                <div class="article-actions mt-5">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button wire:click="toggleLike" class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }} w-100 action-btn">
                                <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}"></i>
                                {{ $likesCount }} Likes
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-primary w-100 action-btn" data-bs-toggle="modal" data-bs-target="#shareModal">
                                <i class="bi bi-share"></i>
                                Share Article
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Author Information -->
                <div class="author-section mt-5">
                    <div class="author-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="{{ $news->author_avatar ?? '/api/placeholder/80/80' }}"
                                     alt="{{ $news->author ?? 'Author' }}"
                                     class="author-avatar">
                            </div>
                            <div class="col">
                                <h5 class="mb-1">{{ $news->author ?? 'Tech Editorial Team' }}</h5>
                                <p class="text-muted mb-2">{{ $news->author_bio ?? 'Experienced tech journalist covering the latest developments in technology and gaming industry.' }}</p>
                                <div class="author-social">
                                    <a href="#" class="text-decoration-none me-3"><i class="bi bi-twitter"></i></a>
                                    <a href="#" class="text-decoration-none me-3"><i class="bi bi-linkedin"></i></a>
                                    <a href="#" class="text-decoration-none"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related News Section -->
    @if($relatedNews && count($relatedNews) > 0)
        <section class="related-news-section">
            <div class="container">
                <h3 class="section-title text-center mb-5">
                    <i class="bi bi-newspaper"></i> Related Articles
                </h3>
                <div class="row g-4">
                    @foreach($relatedNews as $related)
                        <div class="col-lg-4 col-md-6">
                            <div class="related-news-card h-100">
                                <div class="card-image-wrapper">
                                    <img src="{{ $related->featured_image ?? '/api/placeholder/350/200' }}"
                                         alt="{{ $related->title }}"
                                         class="card-img-top">
                                    <div class="card-overlay">
                                        <a href="{{ route('news.show', ['lang' => app()->getLocale(), 'news' => $related->slug]) }}"
                                           wire:navigate
                                           class="btn btn-light rounded-pill">
                                            Read More <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <span class="badge bg-primary rounded-pill mb-2">{{ $related->category }}</span>
                                    <h6 class="card-title">{{ Str::limit($related->title, 60) }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($related->summary, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> {{ $related->created_at->format('M d') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-eye"></i> {{ $related->views ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="shareModalLabel">Share this article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="share-buttons">
                        <a href="#" onclick="shareOnFacebook()" class="share-btn facebook">
                            <i class="bi bi-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" onclick="shareOnTwitter()" class="share-btn twitter">
                            <i class="bi bi-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="#" onclick="shareOnLinkedIn()" class="share-btn linkedin">
                            <i class="bi bi-linkedin"></i>
                            <span>LinkedIn</span>
                        </a>
                        <a href="#" onclick="shareOnWhatsApp()" class="share-btn whatsapp">
                            <i class="bi bi-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>

                    <div class="mt-4">
                        <label for="shareUrl" class="form-label">Copy Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareUrl" value="{{ request()->url() }}" readonly>
                            <button class="btn btn-outline-primary" onclick="copyToClipboard()">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Design Variables */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(45deg, #ff6b6b, #ee5a24);
            --success-gradient: linear-gradient(45deg, #00b894, #00cec9);
            --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
            --hover-shadow: 0 20px 60px rgba(0,0,0,0.15);
            --glass-bg: rgba(255,255,255,0.15);
            --glass-border: rgba(255,255,255,0.2);
        }

        /* Hero Section */
        .news-hero-section {
            background: var(--primary-gradient);
            color: white;
            min-height: 400px;
            display: flex;
            align-items: center;
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .custom-breadcrumb {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
        }

        .article-meta-header {
            animation: fadeInUp 0.6s ease-out;
        }

        .article-title {
            animation: fadeInUp 0.8s ease-out;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .reading-time-badge {
            background: var(--success-gradient);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        /* Image Carousel */
        .news-carousel {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            animation: fadeInUp 1s ease-out;
        }

        .carousel-image {
            height: 500px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .carousel-item:hover .carousel-image {
            transform: scale(1.02);
        }

        .custom-indicators {
            bottom: 20px;
        }

        .custom-indicators button {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            border: 2px solid white;
            background: rgba(255,255,255,0.5);
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .custom-indicators button.active {
            background: white;
            transform: scale(1.2);
        }

        .custom-control {
            width: 50px;
            height: 50px;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }

        .custom-control:hover {
            background: rgba(0,0,0,0.8);
            transform: scale(1.1);
        }

        .carousel-caption {
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            left: 0;
            right: 0;
            padding: 3rem 2rem 2rem;
        }

        .caption-content {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem 2rem;
            display: inline-block;
        }

        /* Article Content */
        .article-content-card {
            background: white;
            border-radius: 25px;
            box-shadow: var(--card-shadow);
            padding: 3rem;
            animation: fadeInUp 1.2s ease-out;
            position: relative;
            overflow: hidden;
        }

        .article-content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .article-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }

        .article-body p {
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        .article-body h2,
        .article-body h3 {
            color: #2c3e50;
            margin: 2.5rem 0 1.5rem 0;
            position: relative;
        }

        .article-body h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--secondary-gradient);
            border-radius: 2px;
        }

        /* Video Section */
        .video-section {
            animation: fadeInUp 1.4s ease-out;
        }

        .video-card {
            background: white;
            border-radius: 25px;
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
            transition: transform 0.3s ease;
        }

        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .video-icon {
            font-size: 3rem;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Action Buttons */
        .article-actions {
            animation: fadeInUp 1.6s ease-out;
        }

        .action-btn {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-width: 2px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Author Section */
        .author-section {
            animation: fadeInUp 1.8s ease-out;
        }

        .author-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
        }

        .author-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.3);
            transition: transform 0.3s ease;
        }

        .author-avatar:hover {
            transform: scale(1.1);
        }

        .author-social a {
            color: rgba(255,255,255,0.8);
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .author-social a:hover {
            color: white;
            transform: translateY(-2px);
        }

        /* Related News */
        .related-news-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 4rem 0;
        }

        .section-title {
            font-weight: 700;
            color: #2c3e50;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--secondary-gradient);
            border-radius: 2px;
        }

        .related-news-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .related-news-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .card-image-wrapper {
            position: relative;
            overflow: hidden;
        }

        .card-image-wrapper img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .related-news-card:hover .card-image-wrapper img {
            transform: scale(1.1);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .related-news-card:hover .card-overlay {
            opacity: 1;
        }

        /* Share Modal */
        .share-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .share-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-radius: 15px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .share-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
        }

        .share-btn i {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }

        .share-btn.facebook { background: linear-gradient(45deg, #3b5998, #4c70ba); }
        .share-btn.twitter { background: linear-gradient(45deg, #1da1f2, #0d8bd9); }
        .share-btn.linkedin { background: linear-gradient(45deg, #0077b5, #005885); }
        .share-btn.whatsapp { background: linear-gradient(45deg, #25d366, #128c7e); }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .news-hero-section {
                min-height: 300px;
                padding: 2rem 0;
            }

            .article-content-card,
            .video-card,
            .author-card {
                padding: 2rem;
                margin: 1rem;
            }

            .carousel-image {
                height: 250px;
            }

            .share-buttons {
                grid-template-columns: 1fr;
            }
        }

        /* Loading States */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize carousel with custom settings
            const carousel = new bootstrap.Carousel(document.querySelector('#newsImageCarousel'), {
                interval: 4000,
                wrap: true,
                pause: 'hover'
            });

            // Add intersection observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe elements for animation
            document.querySelectorAll('.article-content-card, .video-section, .author-section').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
            });
        });

        // Social sharing functions
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank', 'width=600,height=400');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
        }

        function shareOnLinkedIn() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://wa.me/?text=${text} ${url}`, '_blank');
        }

        function copyToClipboard() {
            const input = document.getElementById('shareUrl');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);

            // Show feedback
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-outline-primary');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }, 2000);
        }

        // Livewire hooks
        document.addEventListener('livewire:init', () => {
            Livewire.on('article-liked', (event) => {
                // Add success animation
                const likeBtn = document.querySelector('.btn-danger, .btn-outline-danger');
                likeBtn.classList.add('pulse-animation');
                setTimeout(() => {
                    likeBtn.classList.remove('pulse-animation');
                }, 1000);
            });
        });
    </script>
</div>
