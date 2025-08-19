<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                Comments ({{ number_format($totalComments) }})
            </h3>

            {{-- Sort Options --}}
            <div class="flex space-x-2">
                <button wire:click="setSortBy('newest')"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                               {{ $sortBy === 'newest' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600' }}">
                    Newest
                </button>
                <button wire:click="setSortBy('oldest')"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                               {{ $sortBy === 'oldest' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600' }}">
                    Oldest
                </button>
                <button wire:click="setSortBy('popular')"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                               {{ $sortBy === 'popular' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600' }}">
                    Popular
                </button>
            </div>
        </div>

        {{-- Add Comment Form --}}
        @auth
            <div class="mb-6">
                <div class="flex items-start space-x-4">
                    <img src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-12 h-12 rounded-full ring-2 ring-blue-100">

                    <div class="flex-1">
                        <form wire:submit="addComment" class="space-y-4">
                            <div class="relative">
                                <textarea wire:model.live="newComment"
                                          placeholder="What are your thoughts?"
                                          rows="4"
                                          class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none placeholder-gray-400"
                                          maxlength="2000"></textarea>

                                {{-- Character Counter --}}
                                <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                    {{ strlen($newComment) }}/2000
                                </div>
                            </div>

                            @error('newComment')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            {{-- Comment Actions --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Be respectful and constructive
                                    </span>
                                </div>

                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="addComment">
                                        <i class="fas fa-paper-plane mr-2"></i>Post Comment
                                    </span>
                                    <span wire:loading wire:target="addComment">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Posting...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 p-4 bg-gray-50 rounded-xl text-center">
                <div class="flex items-center justify-center mb-3">
                    <i class="fas fa-user-circle text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-600 mb-4">Join the conversation! Login to share your thoughts.</p>
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('login') }}"
                       class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Sign Up
                    </a>
                </div>
            </div>
        @endauth
    </div>

    {{-- Comments List --}}
    <div class="divide-y divide-gray-200">
        @forelse($comments as $comment)
            <div class="p-6" id="comment-{{ $comment->id }}">
                {{-- Main Comment --}}
                <div class="flex items-start space-x-4">
                    <img src="{{ $comment->user->avatar_url }}"
                         alt="{{ $comment->user->name }}"
                         class="w-12 h-12 rounded-full ring-2 ring-gray-100">

                    <div class="flex-1 min-w-0">
                        {{-- Comment Header --}}
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <h5 class="font-semibold text-gray-900">{{ $comment->user->name }}</h5>

                                @if($comment->user->hasRole('admin'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-shield-alt mr-1"></i>Admin
                                    </span>
                                @elseif($comment->user->hasRole('moderator'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-user-shield mr-1"></i>Moderator
                                    </span>
                                @endif

                                @if($comment->is_featured)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif

                                <span class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>{{ $comment->created_at->diffForHumans() }}
                                </span>

                                @if($comment->created_at != $comment->updated_at)
                                    <span class="text-xs text-gray-400">(edited)</span>
                                @endif
                            </div>

                            {{-- Comment Menu --}}
                            @auth
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <div x-show="open"
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 top-8 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">

                                        @if($comment->can_edit)
                                            <button wire:click="startEdit({{ $comment->id }})"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                <i class="fas fa-edit mr-2"></i>Edit Comment
                                            </button>
                                        @endif

                                        @if($comment->can_delete)
                                            <button wire:click="deleteComment({{ $comment->id }})"
                                                    wire:confirm="Are you sure you want to delete this comment?"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-trash mr-2"></i>Delete Comment
                                            </button>
                                        @endif

                                        @if(!$comment->is_reported && $comment->user_id !== auth()->id())
                                            <button wire:click="openReportModal({{ $comment->id }})"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                <i class="fas fa-flag mr-2"></i>Report Comment
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endauth
                        </div>

                        {{-- Comment Content --}}
                        @if($editingCommentId === $comment->id)
                            {{-- Edit Form --}}
                            <form wire:submit="updateComment" class="space-y-3">
                                <textarea wire:model="editingContent"
                                          rows="4"
                                          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                          maxlength="2000"></textarea>

                                @error('editingContent')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror

                                <div class="flex justify-end space-x-3">
                                    <button type="button"
                                            wire:click="cancelEdit"
                                            class="text-gray-500 hover:text-gray-700 transition-colors px-4 py-2">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        Update Comment
                                    </button>
                                </div>
                            </form>
                        @else
                            {{-- Display Comment --}}
                            <div class="prose prose-sm max-w-none">
                                <p class="text-gray-700 whitespace-pre-line">{{ $comment->formatted_content }}</p>
                            </div>
                        @endif

                        {{-- Comment Actions --}}
                        @if($editingCommentId !== $comment->id)
                            <div class="flex items-center space-x-6 mt-4">
                                {{-- Like Button --}}
                                <button wire:click="toggleLike({{ $comment->id }})"
                                        class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors group">
                                    <i class="fa{{ $comment->is_liked ? 's' : 'r' }} fa-heart {{ $comment->is_liked ? 'text-red-500' : '' }} group-hover:scale-110 transition-transform"></i>
                                    <span class="text-sm font-medium">{{ $comment->likes_count }}</span>
                                </button>

                                {{-- Reply Button --}}
                                @auth
                                    <button wire:click="startReply({{ $comment->id }})"
                                            class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 transition-colors">
                                        <i class="fas fa-reply"></i>
                                        <span class="text-sm font-medium">Reply</span>
                                    </button>
                                @endauth

                                {{-- Share Button --}}
                                <button onclick="copyCommentLink({{ $comment->id }})"
                                        class="flex items-center space-x-2 text-gray-500 hover:text-green-500 transition-colors">
                                    <i class="fas fa-share"></i>
                                    <span class="text-sm font-medium">Share</span>
                                </button>
                            </div>
                        @endif

                        {{-- Reply Form --}}
                        @auth
                            @if($replyTo === $comment->id)
                                <div class="mt-4 pl-4 border-l-2 border-blue-200">
                                    <form wire:submit="addReply" class="space-y-3">
                                        <div class="flex items-start space-x-3">
                                            <img src="{{ auth()->user()->avatar_url }}"
                                                 alt="{{ auth()->user()->name }}"
                                                 class="w-8 h-8 rounded-full ring-1 ring-gray-200">

                                            <div class="flex-1">
                                                <textarea wire:model="replyContent"
                                                          placeholder="Write your reply..."
                                                          rows="3"
                                                          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none text-sm"
                                                          maxlength="1000"></textarea>

                                                @error('replyContent')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="flex justify-end space-x-3 pl-11">
                                            <button type="button"
                                                    wire:click="cancelReply"
                                                    class="text-gray-500 hover:text-gray-700 transition-colors text-sm px-3 py-1">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                <span wire:loading.remove wire:target="addReply">Reply</span>
                                                <span wire:loading wire:target="addReply">Replying...</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endauth

                        {{-- Replies --}}
                        @if($comment->replies->count() > 0)
                            <div class="mt-6 space-y-4">
                                @foreach($comment->replies as $reply)
                                    <div class="flex items-start space-x-3 pl-6 border-l-2 border-gray-100">
                                        <img src="{{ $reply->user->avatar_url }}"
                                             alt="{{ $reply->user->name }}"
                                             class="w-8 h-8 rounded-full ring-1 ring-gray-200">

                                        <div class="flex-1 min-w-0">
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div class="flex items-center space-x-2">
                                                        <h6 class="font-semibold text-gray-900 text-sm">{{ $reply->user->name }}</h6>
                                                        <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                <p class="text-gray-700 text-sm whitespace-pre-line">{{ $reply->formatted_content }}</p>
                                            </div>

                                            {{-- Reply Actions --}}
                                            <div class="flex items-center space-x-4 mt-2">
                                                <button wire:click="toggleLike({{ $reply->id }})"
                                                        class="flex items-center space-x-1 text-gray-400 hover:text-red-500 transition-colors">
                                                    <i class="fa{{ $reply->is_liked ? 's' : 'r' }} fa-heart text-xs {{ $reply->is_liked ? 'text-red-500' : '' }}"></i>
                                                    <span class="text-xs">{{ $reply->likes_count }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            {{-- No Comments --}}
            <div class="p-12 text-center">
                <div class="max-w-sm mx-auto">
                    <i class="far fa-comments text-6xl text-gray-300 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-500 mb-2">No comments yet</h4>
                    <p class="text-gray-400 mb-6">Be the first to share your thoughts on this article!</p>

                    @guest
                        <div class="space-y-3">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login to Comment
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($comments->hasPages())
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            {{ $comments->links() }}
        </div>
    @endif

    {{-- Report Modal --}}
    @if($showReportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" wire:click="closeReportModal">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="submitReport">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-flag text-red-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Report Comment</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                                            <select wire:model="reportReason"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                                <option value="">Select a reason</option>
                                                <option value="spam">Spam</option>
                                                <option value="harassment">Harassment</option>
                                                <option value="inappropriate">Inappropriate Content</option>
                                                <option value="other">Other</option>
                                            </select>
                                            @error('reportReason')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Details (Optional)</label>
                                            <textarea wire:model="reportDescription"
                                                      rows="3"
                                                      placeholder="Provide more details about why you're reporting this comment..."
                                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                                            @error('reportDescription')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <span wire:loading.remove>Submit Report</span>
                                <span wire:loading>Submitting...</span>
                            </button>
                            <button type="button"
                                    wire:click="closeReportModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript for additional functionality --}}
<script>
    function copyCommentLink(commentId) {
        const url = window.location.href + '#comment-' + commentId;
        navigator.clipboard.writeText(url).then(function() {
            // Show toast notification
            showToast('Comment link copied to clipboard!', 'success');
        });
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        } text-white`;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    // Auto-resize textarea
    document.addEventListener('livewire:initialized', () => {
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
    });
</script>
