<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasLocalizedColumns;

class Page extends Model
{
    use HasLocalizedColumns;

    protected $fillable = [
        'slug',
        'title_az', 'title_ru', 'title_en',
        'content_az', 'content_ru', 'content_en',
        'seo_title_az', 'seo_title_ru', 'seo_title_en',
        'seo_description_az', 'seo_description_ru', 'seo_description_en',
        'seo_keywords_az', 'seo_keywords_ru', 'seo_keywords_en',
        'is_active', 'sort_order',
    ];
}
