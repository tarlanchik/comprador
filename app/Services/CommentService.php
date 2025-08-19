<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function createComment(array $data): Comment
    {
        return DB::transaction(function () use ($data) {
            $comment = Comment::create($data);

            // Update cache
            $this->clearCommentCache($comment->news_id);

            return $comment;
        });
    }

    public function updateComment(Comment $comment, array $data): Comment
    {
        return DB::transaction(function () use ($comment, $data) {
            $comment->update($data);

            // Update cache
            $this->clearCommentCache($comment->news_id);

            return $comment;
        });
    }

    public function deleteComment(Comment $comment): bool
    {
        return DB::transaction(function () use ($comment) {
            $newsId = $comment->news_id;
            $deleted = $comment->delete();

            // Update cache
            $this->clearCommentCache($newsId);

            return $deleted;
        });
    }

    public function getCommentsForNews(News $news, array $options = [])
    {
        $cacheKey = "news_{$news->id}_comments_" . md5(serialize($options));

        return Cache::remember($cacheKey, 300, function () use ($news, $options) {
            $query = $news->comments()
                ->with(['user', 'replies.user', 'likes'])
                ->whereNull('parent_id')
                ->where('approved', true);

            // Apply sorting
            $sort = $options['sort'] ?? 'newest';
            switch ($sort) {
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

            $perPage = $options['per_page'] ?? 10;
            return $query->paginate($perPage);
        });
    }

    public function getFeaturedComments(News $news, int $limit = 5)
    {
        $cacheKey = "news_{$news->id}_featured_comments_{$limit}";

        return Cache::remember($cacheKey, 1800, function () use ($news, $limit) {
            return $news->featuredComments()->limit($limit)->get();
        });
    }

    public function getCommentStats(News $news): array
    {
        $cacheKey = "news_{$news->id}_comment_stats";

        return Cache::remember($cacheKey, 900, function () use ($news) {
            return [
                'total' => $news->comments()->approved()->count(),
                'replies' => $news->comments()->approved()->whereNotNull('parent_id')->count(),
                'likes' => $news->comments()->approved()->withCount('likes')->get()->sum('likes_count'),
                'featured' => $news->comments()->where('is_featured', true)->count(),
                'recent' => $news->comments()->approved()->where('created_at', '>=', now()->subDays(7))->count(),
            ];
        });
    }

    public function getUserCommentStats(User $user): array
    {
        $cacheKey = "user_{$user->id}_comment_stats";

        return Cache::remember($cacheKey, 1800, function () use ($user) {
            $comments = $user->comments()->approved();

            return [
                'total' => $comments->count(),
                'likes_received' => $comments->withCount('likes')->get()->sum('likes_count'),
                'replies_received' => Comment::whereIn('parent_id', $comments->pluck('id'))->count(),
                'featured' => $comments->where('is_featured', true)->count(),
                'recent' => $comments->where('created_at', '>=', now()->subDays(30))->count(),
            ];
        });
    }

    public function moderateComment(Comment $comment, string $action): bool
    {
        switch ($action) {
            case 'approve':
                $comment->approve();
                break;
            case 'reject':
                $comment->reject();
                break;
            case 'feature':
                $comment->markAsFeatured();
                break;
            case 'spam':
                $comment->markAsSpam();
                break;
            default:
                return false;
        }

        $this->clearCommentCache($comment->news_id);
        return true;
    }

    public function extractMentions(string $content): array
    {
        preg_match_all('/@(\w+)/', $content, $matches);
        $usernames = $matches[1] ?? [];

        // Validate usernames exist
        $validUsernames = User::whereIn('username', $usernames)->pluck('username')->toArray();

        return $validUsernames;
    }

    public function filterProfanity(string $content): string
    {
        // Simple profanity filter - in production, use a more sophisticated service
        $profaneWords = ['spam', 'stupid', 'idiot']; // Add more words

        foreach ($profaneWords as $word) {
            $content = str_ireplace($word, str_repeat('*', strlen($word)), $content);
        }

        return $content;
    }

    public function canUserComment(User $user): array
    {
        $checks = [
            'rate_limit' => $this->checkRateLimit($user),
            'reputation' => $this->checkReputation($user),
            'banned' => $this->checkBanStatus($user),
        ];

        return [
            'can_comment' => !in_array(false, $checks),
            'checks' => $checks,
            'message' => $this->getRestrictionMessage($checks)
        ];
    }

    private function checkRateLimit(User $user): bool
    {
        $recentComments = $user->comments()
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $recentComments < 10;
    }

    private function checkReputation(User $user): bool
    {
        $reportedComments = $user->comments()
            ->whereHas('reports', function($query) {
                $query->where('status', 'resolved')
                    ->where('created_at', '>=', now()->subDays(30));
            })->count();

        return $reportedComments < 3;
    }

    private function checkBanStatus(User $user): bool
    {
        return !$user->is_banned;
    }

    private function getRestrictionMessage(array $checks): ?string
    {
        if (!$checks['banned']) {
            return 'Your account has been temporarily banned from commenting.';
        }

        if (!$checks['rate_limit']) {
            return 'You have reached the comment limit. Please try again later.';
        }

        if (!$checks['reputation']) {
            return 'Your commenting privileges are limited due to recent reports.';
        }

        return null;
    }

    private function clearCommentCache(int $newsId): void
    {
        $patterns = [
            "news_{$newsId}_comments_*",
            "news_{$newsId}_featured_comments_*",
            "news_{$newsId}_comment_stats"
        ];

        foreach ($patterns as $pattern) {
            Cache::flush(); // In production, use more specific cache clearing
        }
    }
}

