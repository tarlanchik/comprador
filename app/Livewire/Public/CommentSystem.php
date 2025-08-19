<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Comment;
use App\Models\News;
use App\Models\CommentReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CommentSystem extends Component
{
    use WithPagination;

    public $news;
    public $newComment = '';
    public $editingCommentId = null;
    public $editingContent = '';
    public $replyTo = null;
    public $replyContent = '';
    public $showReportModal = false;
    public $reportingCommentId = null;
    public $reportReason = '';
    public $reportDescription = '';
    public $sortBy = 'newest'; // newest, oldest, popular
    public $showApprovedOnly = true;

    protected $rules = [
        'newComment' => 'required|min:3|max:2000|profanity_filter',
        'replyContent' => 'required|min:3|max:1000|profanity_filter',
        'editingContent' => 'required|min:3|max:2000|profanity_filter',
        'reportReason' => 'required|in:spam,harassment,inappropriate,other',
        'reportDescription' => 'nullable|max:500'
    ];

    protected $messages = [
        'newComment.profanity_filter' => 'Your comment contains inappropriate language.',
        'replyContent.profanity_filter' => 'Your reply contains inappropriate language.',
        'editingContent.profanity_filter' => 'Your comment contains inappropriate language.'
    ];

    public function mount(News $news)
    {
        $this->news = $news;
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to comment.');
            return redirect()->route('login');
        }

        // Rate limiting
        $key = 'comment-submit:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'newComment' => "Too many attempts. Please try again in {$seconds} seconds."
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        $this->validate([
            'newComment' => 'required|min:3|max:2000'
        ]);

        // Check if user can comment
        if (!auth()->user()->canComment()) {
            session()->flash('error', 'You have reached the comment limit. Please try again later.');
            return;
        }

        // Extract mentions
        $mentions = $this->extractMentions($this->newComment);

        // Create comment
        $comment = Comment::create([
            'news_id' => $this->news->id,
            'user_id' => auth()->id(),
            'content' => $this->newComment,
            'approved' => $this->shouldAutoApprove(),
            'mentions' => $mentions
        ]);

        $this->newComment = '';

        if ($comment->approved) {
            session()->flash('success', 'Comment added successfully!');
        } else {
            session()->flash('info', 'Your comment is pending approval.');
        }

        $this->dispatch('commentAdded');
        $this->resetPage();
    }

    public function addReply()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to reply.');
            return redirect()->route('login');
        }

        // Rate limiting
        $key = 'reply-submit:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'replyContent' => "Too many attempts. Please try again in {$seconds} seconds."
            ]);
        }

        RateLimiter::hit($key, 180); // 3 minutes

        $this->validate([
            'replyContent' => 'required|min:3|max:1000'
        ]);

        if (!auth()->user()->canComment()) {
            session()->flash('error', 'You have reached the comment limit. Please try again later.');
            return;
        }

        $parentComment = Comment::find($this->replyTo);
        if (!$parentComment) {
            session()->flash('error', 'Parent comment not found.');
            return;
        }

        $mentions = $this->extractMentions($this->replyContent);

        Comment::create([
            'news_id' => $this->news->id,
            'user_id' => auth()->id(),
            'parent_id' => $this->replyTo,
            'content' => $this->replyContent,
            'approved' => $this->shouldAutoApprove(),
            'mentions' => $mentions
        ]);

        $this->replyContent = '';
        $this->replyTo = null;

        session()->flash('success', 'Reply added successfully!');
        $this->dispatch('replyAdded');
    }

    public function startEdit($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment || !$comment->can_edit) {
            session()->flash('error', 'You cannot edit this comment.');
            return;
        }

        $this->editingCommentId = $commentId;
        $this->editingContent = $comment->content;
    }

    public function updateComment()
    {
        $this->validate([
            'editingContent' => 'required|min:3|max:2000'
        ]);

        $comment = Comment::find($this->editingCommentId);

        if (!$comment || !$comment->can_edit) {
            session()->flash('error', 'You cannot edit this comment.');
            return;
        }

        $mentions = $this->extractMentions($this->editingContent);

        $comment->update([
            'content' => $this->editingContent,
            'mentions' => $mentions,
            'approved' => $this->shouldAutoApprove()
        ]);

        $this->editingCommentId = null;
        $this->editingContent = '';

        session()->flash('success', 'Comment updated successfully!');
    }

    public function cancelEdit()
    {
        $this->editingCommentId = null;
        $this->editingContent = '';
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment || !$comment->can_delete) {
            session()->flash('error', 'You cannot delete this comment.');
            return;
        }

        $comment->delete();
        session()->flash('success', 'Comment deleted successfully!');
        $this->dispatch('commentDeleted');
    }

    public function toggleLike($commentId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to like comments.');
            return;
        }

        $comment = Comment::find($commentId);
        if (!$comment) {
            return;
        }

        $isLiked = $comment->toggleLike();

        $this->dispatch('commentLiked', [
            'commentId' => $commentId,
            'isLiked' => $isLiked,
            'likesCount' => $comment->likes()->count()
        ]);
    }

    public function startReply($commentId)
    {
        if ($this->replyTo === $commentId) {
            $this->replyTo = null;
            $this->replyContent = '';
        } else {
            $this->replyTo = $commentId;
            $this->replyContent = '';
        }
    }

    public function cancelReply()
    {
        $this->replyTo = null;
        $this->replyContent = '';
    }

    public function openReportModal($commentId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to report comments.');
            return;
        }

        $this->reportingCommentId = $commentId;
        $this->showReportModal = true;
        $this->reportReason = '';
        $this->reportDescription = '';
    }

    public function submitReport()
    {
        if (!Auth::check()) {
            return;
        }

        $this->validate([
            'reportReason' => 'required|in:spam,harassment,inappropriate,other',
            'reportDescription' => 'nullable|max:500'
        ]);

        $comment = Comment::find($this->reportingCommentId);
        if (!$comment) {
            session()->flash('error', 'Comment not found.');
            return;
        }

        // Check if already reported by this user
        $existingReport = CommentReport::where('comment_id', $this->reportingCommentId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReport) {
            session()->flash('error', 'You have already reported this comment.');
            $this->closeReportModal();
            return;
        }

        CommentReport::create([
            'comment_id' => $this->reportingCommentId,
            'user_id' => auth()->id(),
            'reason' => $this->reportReason,
            'description' => $this->reportDescription
        ]);

        session()->flash('success', 'Comment reported successfully. We will review it shortly.');
        $this->closeReportModal();
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->reportingCommentId = null;
        $this->reportReason = '';
        $this->reportDescription = '';
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    private function extractMentions($content)
    {
        preg_match_all('/@(\w+)/', $content, $matches);
        return $matches[1] ?? [];
    }

    private function shouldAutoApprove()
    {
        $user = auth()->user();

        // Auto-approve for trusted users
        if ($user->hasRole('admin') || $user->hasRole('moderator')) {
            return true;
        }

        // Auto-approve for users with good comment history
        if ($user->comments()->where('approved', true)->count() >= 5) {
            $recentReports = CommentReport::whereHas('comment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('created_at', '>=', now()->subDays(30))->count();

            return $recentReports === 0;
        }

        // New users need approval
        return false;
    }

    public function render()
    {
        $query = Comment::where('news_id', $this->news->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'likes']);

        if ($this->showApprovedOnly) {
            $query->where('approved', true);
        }

        // Sorting
        switch ($this->sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $comments = $query->paginate(10);

        return view('livewire.comment-system', [
            'comments' => $comments,
            'totalComments' => $this->news->comments()->approved()->count()
        ]);
    }
}
