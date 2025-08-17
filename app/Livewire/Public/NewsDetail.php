<?php

namespace App\Livewire\Public;

use App\Models\News;
use Illuminate\Support\Str;
use Livewire\Component;

class NewsDetail extends Component
{
    public $news;
    public $relatedNews;

    public function mount(News $news): void
    {
        $this->news = $news->load('images');
        $this->loadRelatedNews();
    }

    public function loadRelatedNews(): void
    {
        $this->relatedNews = News::with('images')
            ->where('id', '!=', $this->news->id)
            ->latest()
            ->limit(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.public.news-detail')
            ->layout('layouts.public')
            ->title($this->news->{'title_' . app()->getLocale()})
            ->section('description', Str::limit(strip_tags($this->news->{'content_' . app()->getLocale()}), 160));
    }
}

