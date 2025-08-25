<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_active',
        'sort_order',
    ];

    public $translatable = ['title','content','seo_title','seo_description','seo_keywords'];


}
