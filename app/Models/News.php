<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//use Illuminate\Database\Eloquent\Casts\Attribute;

class News extends Model
{
    use HasLocalizedColumns;

    protected $fillable = [
        'title_az', 'title_ru', 'title_en',
        'content_az', 'content_ru', 'content_en',
        'keywords_az', 'keywords_ru', 'keywords_en',
        'description_az', 'description_ru', 'description_en',
        'youtube_link', 'views', 'author_id', 'category_id','slug',
    ];

    protected $casts = [
        'views' => 'integer',
       // 'images' => 'array',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'news_tags');
    }

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

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->where('approved', true)->count();
    }

    public function canComment(): bool
    {
        return property_exists($this, 'comments_enabled')
            ? $this->comments_enabled
            : true;
    }

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }
}
