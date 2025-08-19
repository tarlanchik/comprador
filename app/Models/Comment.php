<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'user_id',
        'parent_id',
        'content',
        'approved',
        'is_featured',
        'mentions'
    ];

    protected $casts = [
        'approved' => 'boolean',
        'is_featured' => 'boolean',
        'mentions' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['user'];

    // Relationships
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('approved', true)
            ->with(['user', 'likes'])
            ->latest();
    }

    public function allReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CommentReport::class);
    }

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true);
    }

    public function scopeParentComments(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->withCount('likes')
            ->orderBy('likes_count', 'desc');
    }

    // Accessors & Mutators
    public function getIsLikedAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getRepliesCountAttribute(): int
    {
        return $this->replies()->count();
    }

    public function getIsReportedAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->reports()->where('user_id', auth()->id())->exists();
    }

    public function getCanEditAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        // User can edit their own comment within 30 minutes
        $canEdit = auth()->id() === $this->user_id &&
            $this->created_at->diffInMinutes() <= 30;

        // Or if user is admin/moderator
        $isAdmin = auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator');

        return $canEdit || $isAdmin;
    }

    public function getCanDeleteAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $canDelete = auth()->id() === $this->user_id;
        $isAdmin = auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator');

        return $canDelete || $isAdmin;
    }

    public function getFormattedContentAttribute(): string
    {
        $content = e($this->content);

        // Convert URLs to links
        $content = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener" class="text-blue-600 hover:underline">$1</a>',
            $content
        );

        // Convert @mentions to links
        if ($this->mentions) {
            foreach ($this->mentions as $mention) {
                $content = str_replace(
                    '@' . $mention,
                    '<a href="/profile/' . $mention . '" class="text-blue-600 hover:underline">@' . $mention . '</a>',
                    $content
                );
            }
        }

        return nl2br($content);
    }

    // Methods
    public function toggleLike(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $existingLike = $this->likes()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            $existingLike->delete();
            return false;
        } else {
            $this->likes()->create(['user_id' => auth()->id()]);

            // Send notification to comment author (if not same user)
            if ($this->user_id !== auth()->id()) {
                $this->user->notify(new CommentLikedNotification($this, auth()->user()));
            }

            return true;
        }
    }

    public function approve(): void
    {
        $this->update(['approved' => true]);

        // Update news comment count
        $this->news->increment('comments_count');
        $this->news->update(['last_commented_at' => $this->created_at]);
    }

    public function reject(): void
    {
        $this->update(['approved' => false]);

        // Update news comment count
        $this->news->decrement('comments_count');
    }

    public function markAsFeatured(): void
    {
        $this->update(['is_featured' => true]);
    }

    public function markAsSpam(): void
    {
        $this->update(['approved' => false]);

        // You might want to add the user to a spam list or take other actions
        // $this->user->markAsSpammer();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            if ($comment->approved) {
                // Update news comments count
                $comment->news->increment('comments_count');
                $comment->news->update(['last_commented_at' => $comment->created_at]);

                // Update user's last commented time
                $comment->user->update(['last_commented_at' => $comment->created_at]);

                // Send notifications
                if ($comment->parent_id) {
                    // Reply notification
                    $comment->parent->user->notify(new CommentReplyNotification($comment));
                } else {
                    // New comment notification to article author
                    if ($comment->news->author_id !== $comment->user_id) {
                        $comment->news->author->notify(new NewCommentNotification($comment));
                    }
                }
            }
        });

        static::updated(function ($comment) {
            if ($comment->isDirty('approved')) {
                if ($comment->approved) {
                    $comment->news->increment('comments_count');
                } else {
                    $comment->news->decrement('comments_count');
                }
            }
        });

        static::deleted(function ($comment) {
            if ($comment->approved) {
                $comment->news->decrement('comments_count');
            }
        });
    }
}






