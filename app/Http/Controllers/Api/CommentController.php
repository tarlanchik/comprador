<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\News;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Request $request, News $news)
    {
        $query = $news->comments()
            ->with(['user', 'replies.user', 'likes'])
            ->whereNull('parent_id')
            ->where('approved', true);

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $comments = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $comments,
            'meta' => [
                'total_comments' => $news->comments_count
            ]
        ]);
    }

    public function store(CommentRequest $request, News $news)
    {
        if (!$news->canComment()) {
            return response()->json([
                'success' => false,
                'message' => 'Comments are disabled for this article.'
            ], 403);
        }

        if (!auth()->user()->canComment()) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the comment limit. Please try again later.'
            ], 429);
        }

        $mentions = $this->extractMentions($request->content);

        $comment = Comment::create([
            'news_id' => $news->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'approved' => $this->shouldAutoApprove(),
            'mentions' => $mentions
        ]);

        $comment->load(['user', 'likes']);

        return response()->json([
            'success' => true,
            'message' => $comment->approved ? 'Comment posted successfully.' : 'Comment submitted for approval.',
            'data' => $comment
        ], 201);
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        if (!$comment->can_edit) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot edit this comment.'
            ], 403);
        }

        $mentions = $this->extractMentions($request->content);

        $comment->update([
            'content' => $request->content,
            'mentions' => $mentions,
            'approved' => $this->shouldAutoApprove()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully.',
            'data' => $comment->load(['user', 'likes'])
        ]);
    }

    public function destroy(Comment $comment)
    {
        if (!$comment->can_delete) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }

    public function like(Comment $comment)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to like comments.'
            ], 401);
        }

        $isLiked = $comment->toggleLike();

        return response()->json([
            'success' => true,
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $comment->likes()->count()
            ]
        ]);
    }

    public function report(Request $request, Comment $comment)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to report comments.'
            ], 401);
        }

        $request->validate([
            'reason' => 'required|in:spam,harassment,inappropriate,other',
            'description' => 'nullable|max:500'
        ]);

        // Check if already reported by this user
        $existingReport = $comment->reports()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this comment.'
            ], 400);
        }

        $comment->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment reported successfully. We will review it shortly.'
        ]);
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
            $recentReports = $user->comments()
                ->whereHas('reports', function($query) {
                    $query->where('created_at', '>=', now()->subDays(30));
                })->count();

            return $recentReports === 0;
        }
        return false;
    }
}
