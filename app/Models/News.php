<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends Model
{
    protected $fillable = [
        'title', 'excerpt', 'content', 'image', 'slug',
        'author_id', 'published_at', 'views',
        'title_az', 'content_az', 'title_ru', 'content_ru', 'title_en', 'content_en',
        'keywords_az', 'keywords_ru', 'keywords_en',
        'description_az', 'description_ru', 'description_en',
        'youtube_link', 'featured_image', 'reading_time'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Author relationship
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Category relationship - THIS WAS MISSING!
   public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Tags relationship - THIS WAS ALSO MISSING!
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'news_tags');
    }

    // Comment relationships
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->where('approved', true)
            ->whereNull('parent_id')
            ->with(['user', 'replies', 'likes'])
            ->latest();
    }

    public function featuredComments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->where('approved', true)
            ->where('is_featured', true)
            ->with(['user', 'likes'])
            ->latest();
    }

    public function latestComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'last_commented_at');
    }

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->where('approved', true)->count();
    }

    public function canComment(): bool
    {
        return $this->comments_enabled && $this->published_at <= now();
    }

    // Images relationship
    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }
}
