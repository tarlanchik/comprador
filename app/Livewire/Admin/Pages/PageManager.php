<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Page;

#[Layout('admin.layouts.admin')]
class PageManager extends Component
{
    public array $locales = [];

    public function mount(): void
    {
        $this->locales = config('app.locales');
    }
    public function render()
    {
        $pages = Page::all();
        return view('livewire.admin.pages.manager', [
            'pages' => $pages,
            'locales' => $this->locales,
        ]);
    }
}
