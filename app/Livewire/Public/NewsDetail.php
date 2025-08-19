<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\News;
use App\Models\Comment;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class NewsDetail extends Component
{
    use WithPagination;

    public $news;
    public $slug;
    public $relatedNews = [];

    public string $newComment = '';
    public $replyTo = null;
    public string $replyContent = '';
    public bool $showReplyForm = false;

    // Social sharing
    public $shareUrl;
    public $shareTitle;

    // Reading progress
    public int $readingProgress = 0;

    protected $rules = [
        'newComment' => 'required|min:3|max:1000',
        'replyContent' => 'required|min:3|max:500',
    ];

    public function mount(News $news)
    {
        // Check if news is published
        if ($news->published_at > now()) {
            abort(404);
        }

        // Load relationships if not already loaded
        $this->news = $news->load(['author', 'category', 'tags']);
        $this->slug = $news->slug;

        // Increment view count
        $this->news->increment('views');

        // Get related news
        $this->relatedNews = News::where('id', '!=', $this->news->id)
            ->where('category_id', $this->news->category_id)
            ->where('created_at', '<=', now())
            ->latest()
            ->limit(3)
            ->get();

        // Setup sharing
        $this->shareUrl = url()->current();
        $this->shareTitle = $this->news->title;
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add a comment.');
            return redirect()->route('login');
        }

        $this->validate([
            'newComment' => 'required|min:3|max:1000'
        ]);

        Comment::create([
            'news_id' => $this->news->id,
            'user_id' => Auth::id(),
            'content' => $this->newComment,
            'approved' => true, // You might want to moderate comments
        ]);

        $this->newComment = '';
        session()->flash('success', 'Comment added successfully!');

        // Refresh comments
        $this->dispatch('commentAdded');
    }

    public function toggleReplyForm($commentId)
    {
        $this->replyTo = $this->replyTo === $commentId ? null : $commentId;
        $this->showReplyForm = $this->replyTo !== null;
        $this->replyContent = '';
    }

    public function addReply()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to reply.');
            return redirect()->route('login');
        }

        $this->validate([
            'replyContent' => 'required|min:3|max:500'
        ]);

        Comment::create([
            'news_id' => $this->news->id,
            'user_id' => Auth::id(),
            'parent_id' => $this->replyTo,
            'content' => $this->replyContent,
            'approved' => true,
        ]);

        $this->replyContent = '';
        $this->replyTo = null;
        $this->showReplyForm = false;

        session()->flash('success', 'Reply added successfully!');
        $this->dispatch('commentAdded');
    }

    public function likeComment($commentId)
    {
        if (!Auth::check()) {
            return;
        }

        $comment = Comment::find($commentId);
        $existingLike = $comment->likes()->where('user_id', Auth::id())->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            $comment->likes()->create([
                'user_id' => Auth::id()
            ]);
        }

        $this->dispatch('commentLiked');
    }

    public function updateReadingProgress($progress)
    {
        $this->readingProgress = $progress;
    }

    public function render()
    {
        $comments = Comment::where('news_id', $this->news->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'likes'])
            ->latest()
            ->paginate(10);

        return view('livewire.public.news-detail', [
            'comments' => $comments
        ]);
    }
}
