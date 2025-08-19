<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\News;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'news', 'reports'])
            ->withCount(['likes', 'replies']);

        // Filters
        if ($request->filled('status')) {
            $query->where('approved', $request->status === 'approved');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('news', function($newsQuery) use ($search) {
                        $newsQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('news_id')) {
            $query->where('news_id', $request->news_id);
        }

        if ($request->filled('reported')) {
            $query->has('reports');
        }

        $comments = $query->latest()->paginate(20);

        $stats = $this->getCommentStats();

        return view('admin.comments.index', compact('comments', 'stats'));
    }

    public function show(Comment $comment)
    {
        $comment->load(['user', 'news', 'parent', 'replies.user', 'likes.user', 'reports.user']);

        return view('admin.comments.show', compact('comment'));
    }

    public function approve(Comment $comment)
    {
        $comment->approve();

        return back()->with('success', 'Comment approved successfully.');
    }

    public function reject(Comment $comment)
    {
        $comment->reject();

        return back()->with('success', 'Comment rejected successfully.');
    }

    public function feature(Comment $comment)
    {
        $comment->markAsFeatured();

        return back()->with('success', 'Comment marked as featured.');
    }

    public function unfeature(Comment $comment)
    {
        $comment->update(['is_featured' => false]);

        return back()->with('success', 'Comment unfeatured.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete,feature',
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id'
        ]);

        $comments = Comment::whereIn('id', $request->comment_ids);

        switch ($request->action) {
            case 'approve':
                $comments->update(['approved' => true]);
                $message = 'Comments approved successfully.';
                break;
            case 'reject':
                $comments->update(['approved' => false]);
                $message = 'Comments rejected successfully.';
                break;
            case 'feature':
                $comments->update(['is_featured' => true]);
                $message = 'Comments marked as featured.';
                break;
            case 'delete':
                $comments->delete();
                $message = 'Comments deleted successfully.';
                break;
        }

        return back()->with('success', $message);
    }

    public function reports(Request $request)
    {
        $query = CommentReport::with(['comment.user', 'comment.news', 'user'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(20);

        return view('admin.comments.reports', compact('reports'));
    }

    public function resolveReport(CommentReport $report)
    {
        $report->markAsResolved();

        return back()->with('success', 'Report resolved successfully.');
    }

    public function dismissReport(CommentReport $report)
    {
        $report->delete();

        return back()->with('success', 'Report dismissed successfully.');
    }

    private function getCommentStats()
    {
        return [
            'total' => Comment::count(),
            'approved' => Comment::where('approved', true)->count(),
            'pending' => Comment::where('approved', false)->count(),
            'featured' => Comment::where('is_featured', true)->count(),
            'reported' => Comment::has('reports')->count(),
            'today' => Comment::whereDate('created_at', today())->count(),
            'this_week' => Comment::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => Comment::where('created_at', '>=', now()->subMonth())->count(),
        ];
    }
}

// app/Http/Controllers/Api/CommentController.php


// routes/api.php - Add these routes


// routes/web.php - Add admin routes


// Add to your main news route
Route::get('/news/{slug}', function($slug) {
    $news = News::where('slug', $slug)->firstOrFail();
    return view('news.detail', compact('news'));
})->name('news.detail');
