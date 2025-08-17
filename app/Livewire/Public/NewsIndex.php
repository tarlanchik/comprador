<?php

namespace App\Livewire\Public;

use App\Models\News;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    public $searchQuery = '';

    public function updatedSearchQuery(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $locale = app()->getLocale();

        $news = News::with('images')
            ->when($this->searchQuery, function ($query) use ($locale) {
                $query->where("title_{$locale}", 'like', "%{$this->searchQuery}%")
                    ->orWhere("content_{$locale}", 'like', "%{$this->searchQuery}%");
            })
            ->latest()
            ->paginate(9);

        return view('livewire.public.news-index', [
            'newsItems' => $news
        ])->layout('layouts.public');
    }
}





