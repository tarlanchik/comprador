<div class="min-h-screen bg-gray-50">
    {{-- Reading Progress Bar --}}
    <div class="fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
        <div class="h-full bg-gradient-to-r from-blue-500 to-purple-600 transition-all duration-300 ease-out"
             style="width: {{ $readingProgress }}%"></div>
    </div>

    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 overflow-hidden">
        @if($news->featured_image)
            <div class="absolute inset-0">
                <img src="{{ Storage::url($news->featured_image) }}"
                     alt="{{ $news->title_ru }}"
                     class="w-full h-full object-cover opacity-30">
                <div class="absolute inset-0 bg-black opacity-50"></div>
            </div>
        @endif

        <div class="relative container mx-auto px-6 py-20">
            <div class="max-w-4xl mx-auto text-center text-white">
                {{-- Category Badge --}}
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-{{ $news->category->color ?? 'blue' }}-600 text-sm font-semibold mb-6">
                    <i class="fas fa-{{ $news->category->icon ?? 'newspaper' }} mr-2"></i>
                    {{ $news->category->name_en }}
                </div>

                {{-- Title --}}
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    {{ $news->title_ru }}
                </h1>

                {{-- Meta Information --}}
                <div class="flex flex-wrap items-center justify-center gap-6 text-gray-300 mb-8">
                    <div class="flex items-center">
                        <img src="{{ $news->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($news->author->name) }}"
                             alt="{{ $news->author->name ?? 'Неизвестный автор' }}"
                             class="w-10 h-10 rounded-full mr-3">
                        <span>{{ $news->author->name ?? 'Неизвестный автор' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="far fa-calendar mr-2"></i>
                        <span>{{ $news->created_at->format('F j, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="far fa-clock mr-2"></i>
                        <span>{{ $news->reading_time ?? '5' }} min read</span>
                    </div>
                    <div class="flex items-center">
                        <i class="far fa-eye mr-2"></i>
                        <span>{{ number_format($news->views) }} views</span>
                    </div>
                </div>

                {{-- Social Share Buttons --}}
                <div class="flex justify-center space-x-4">
                    <button onclick="shareOnFacebook()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full transition-colors duration-300">
                        <i class="fab fa-facebook-f mr-2"></i>Share
                    </button>
                    <button onclick="shareOnTwitter()"
                            class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-full transition-colors duration-300">
                        <i class="fab fa-twitter mr-2"></i>Tweet
                    </button>
                    <button onclick="shareOnLinkedIn()"
                            class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-full transition-colors duration-300">
                        <i class="fab fa-linkedin-in mr-2"></i>Share
                    </button>
                    <button onclick="copyToClipboard()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-full transition-colors duration-300">
                        <i class="fas fa-link mr-2"></i>Copy
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {{-- Article Content --}}
            <div class="lg:col-span-2">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8 md:p-12" id="article-content">
                        {{-- Article Image --}}
                        @if($news->featured_image)
                            <img src="{{ Storage::url($news->featured_image) }}"
                                 alt="{{ $news->title_ru }}"
                                 class="w-full h-64 md:h-96 object-cover rounded-xl mb-8">
                        @endif

                        {{-- Article Excerpt --}}
                        @if($news->excerpt)
                            <div class="text-xl text-gray-600 leading-relaxed mb-8 font-light border-l-4 border-blue-500 pl-6">
                                {{ $news->excerpt }}
                            </div>
                        @endif

                        {{-- Article Content --}}
                        <div class="prose prose-lg max-w-none">
                            {!! $news->content !!}
                        </div>

                        {{-- Tags --}}
                        @if($news->tags && $news->tags->count() > 0)
                            <div class="mt-12 pt-8 border-t border-gray-200">
                                <h4 class="text-lg font-semibold mb-4 text-gray-800">Tags:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($news->tags as $tag)
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 transition-colors cursor-pointer">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Author Bio --}}
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex items-start space-x-4">
                                <img src="{{ $news->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($news->author->name ?? 'Неизвестный автор') }}"
                                     alt="{{ $news->author->name ?? 'Неизвестный автор' }}"
                                     class="w-16 h-16 rounded-full">
                                <div class="flex-1">
                                    <h4 class="text-xl font-semibold text-gray-800">{{ $news->author->name ?? 'Неизвестный автор' }}</h4>
                                    @if($news->author->bio)
                                        <p class="text-gray-600 mt-2">{{ $news->author->bio }}</p>
                                    @endif
                                    <div class="flex space-x-3 mt-3">
                                        @if($news->author->twitter)
                                            <a href="{{ $news->author->twitter }}" class="text-blue-500 hover:text-blue-600">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        @endif
                                        @if($news->author->linkedin)
                                            <a href="{{ $news->author->linkedin }}" class="text-blue-700 hover:text-blue-800">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Comments Section --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-8">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">
                            Comments ({{ $comments->total() }})
                        </h3>

                        {{-- Add Comment Form --}}
                        @auth
                            <div class="mb-8">
                                <div class="flex items-start space-x-4">
                                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <form wire:submit="addComment">
                                            <textarea wire:model="newComment"
                                                      placeholder="Share your thoughts..."
                                                      rows="4"
                                                      class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                            @error('newComment')
                                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                                            @enderror
                                            <div class="flex justify-between items-center mt-4">
                                                <span class="text-sm text-gray-500">
                                                    {{ strlen($newComment) }}/1000 characters
                                                </span>
                                                <button type="submit"
                                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300 disabled:opacity-50"
                                                        wire:loading.attr="disabled">
                                                    <span wire:loading.remove>Post Comment</span>
                                                    <span wire:loading>Posting...</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-8 p-4 bg-gray-100 rounded-xl text-center">
                                <p class="text-gray-600 mb-4">Please login to leave a comment</p>
                                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Login
                                </a>
                            </div>
                        @endauth

                        {{-- Comments List --}}
                        <div class="space-y-6">
                            @forelse($comments as $comment)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                    {{-- Main Comment --}}
                                    <div class="flex items-start space-x-4">
                                        <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                                             alt="{{ $comment->user->name }}"
                                             class="w-12 h-12 rounded-full">
                                        <div class="flex-1">
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h5 class="font-semibold text-gray-800">{{ $comment->user->name }}</h5>
                                                    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-700">{{ $comment->content }}</p>
                                            </div>

                                            {{-- Comment Actions --}}
                                            <div class="flex items-center space-x-4 mt-3">
                                                <button wire:click="likeComment({{ $comment->id }})"
                                                        class="flex items-center text-gray-500 hover:text-blue-600 transition-colors">
                                                    <i class="far fa-heart mr-1"></i>
                                                    <span>{{ $comment->likes->count() }}</span>
                                                </button>
                                                @auth
                                                    <button wire:click="toggleReplyForm({{ $comment->id }})"
                                                            class="text-gray-500 hover:text-blue-600 transition-colors">
                                                        Reply
                                                    </button>
                                                @endauth
                                            </div>

                                            {{-- Reply Form --}}
                                            @auth
                                                @if($replyTo === $comment->id && $showReplyForm)
                                                    <div class="mt-4">
                                                        <form wire:submit="addReply">
                                                            <textarea wire:model="replyContent"
                                                                      placeholder="Write your reply..."
                                                                      rows="3"
                                                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                                            @error('replyContent')
                                                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                                                            @enderror
                                                            <div class="flex justify-end space-x-3 mt-3">
                                                                <button type="button"
                                                                        wire:click="toggleReplyForm({{ $comment->id }})"
                                                                        class="text-gray-500 hover:text-gray-700 transition-colors">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                                    Reply
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endauth

                                            {{-- Replies --}}
                                            @if($comment->replies && $comment->replies->count() > 0)
                                                <div class="ml-6 mt-4 space-y-4">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="flex items-start space-x-3">
                                                            <img src="{{ $reply->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}"
                                                                 alt="{{ $reply->user->name }}"
                                                                 class="w-10 h-10 rounded-full">
                                                            <div class="flex-1 bg-gray-50 rounded-lg p-3">
                                                                <div class="flex items-center justify-between mb-1">
                                                                    <h6 class="font-semibold text-gray-800 text-sm">{{ $reply->user->name }}</h6>
                                                                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <i class="far fa-comments text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if($comments->hasPages())
                            <div class="mt-8">
                                {{ $comments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                {{-- Sticky Sidebar --}}
                <div class="sticky top-8 space-y-8">
                    {{-- Share Widget --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-800">Share this article</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="shareOnFacebook()" class="flex items-center justify-center bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button onclick="shareOnTwitter()" class="flex items-center justify-center bg-sky-500 text-white p-3 rounded-lg hover:bg-sky-600 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button onclick="shareOnLinkedIn()" class="flex items-center justify-center bg-blue-700 text-white p-3 rounded-lg hover:bg-blue-800 transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                            <button onclick="copyToClipboard()" class="flex items-center justify-center bg-gray-600 text-white p-3 rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Related Articles --}}
                    @if($relatedNews && $relatedNews->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h4 class="text-lg font-semibold mb-6 text-gray-800">Related Articles</h4>
                            <div class="space-y-4">
                                @foreach($relatedNews as $related)
                                    <article class="group">
                                        <a href="{{ route('news.show', $related->slug) }}" class="block">
                                            <div class="flex space-x-3">
                                                @if($related->featured_image)
                                                    <img src="{{ Storage::url($related->featured_image) }}"
                                                         alt="{{ $related->title }}"
                                                         class="w-20 h-20 object-cover rounded-lg group-hover:opacity-80 transition-opacity">
                                                @endif
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2">
                                                        {{ $related->title }}
                                                    </h5>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $related->published_at->format('M j, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Newsletter Subscription --}}
                    <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                        <h4 class="text-lg font-semibold mb-3">Stay Updated</h4>
                        <p class="text-blue-100 mb-4 text-sm">Subscribe to get the latest news and updates delivered to your inbox.</p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your email address"
                                   class="w-full p-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                            <button type="submit"
                                    class="w-full bg-white text-blue-600 p-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    {{-- JavaScript --}}
    <script>
        // Social Sharing Functions
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent('{{ $news->title }}');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
        }

        function shareOnLinkedIn() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent('{{ $news->title }}');
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}`, '_blank', 'width=600,height=400');
        }

        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Link copied to clipboard!');
            });
        }

        // Reading Progress Tracker
        document.addEventListener('DOMContentLoaded', function() {
            const article = document.getElementById('article-content');
            if (article) {
                window.addEventListener('scroll', function() {
                    const articleTop = article.offsetTop;
                    const articleHeight = article.offsetHeight;
                    const scrollTop = window.pageYOffset;
                    const windowHeight = window.innerHeight;

                    const progress = Math.min(100, Math.max(0,
                        (scrollTop - articleTop + windowHeight) / articleHeight * 100
                    ));

                @this.call('updateReadingProgress', Math.round(progress));
                });
            }
        });
    </script>

    {{-- Styles --}}
    <style>
        .prose {
            max-width: none;
            line-height: 1.75;
        }

        .prose p {
            margin-bottom: 1.5em;
            color: #374151;
        }

        .prose h2, .prose h3, .prose h4 {
            color: #1f2937;
            font-weight: 600;
            margin-top: 2em;
            margin-bottom: 1em;
        }

        .prose h2 {
            font-size: 1.875rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }

        .prose h3 {
            font-size: 1.5rem;
        }

        .prose h4 {
            font-size: 1.25rem;
        }

        .prose blockquote {
            border-left: 4px solid #3b82f6;
            background: #f8fafc;
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #475569;
        }

        .prose ul, .prose ol {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose img {
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

    </style>
