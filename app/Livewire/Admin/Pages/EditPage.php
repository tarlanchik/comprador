<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Page;

class EditPage extends Component
{
    public Page $page;

    public array $locales = [];

    // Динамические свойства для контента и SEO
    public array $titles = [];
    public array $contents = [];
    public array $seo_titles = [];
    public array $seo_descriptions = [];
    public array $seo_keywords = [];

    public int $is_active;
    public int $sort_order;

    public function mount(Page $page): void
    {
        $this->page = $page;
        $this->locales = config('app.locales'); // ['az' => 'Azərbaycan', 'ru' => 'Русский', 'en' => 'English']

        foreach ($this->locales as $locale => $label) {
            $this->titles[$locale] = $page->{'title_'.$locale} ?? '';
            $this->contents[$locale] = $page->{'content_'.$locale} ?? '';
            $this->seo_titles[$locale] = $page->{'seo_title_'.$locale} ?? '';
            $this->seo_descriptions[$locale] = $page->{'seo_description_'.$locale} ?? '';
            $this->seo_keywords[$locale] = $page->{'seo_keywords_'.$locale} ?? '';
        }

        $this->is_active = $page->is_active;
        $this->sort_order = $page->sort_order;
    }

    public function save(): void
    {
        // Валидируем динамически
        $rules = [];
        foreach ($this->locales as $locale => $label) {
            $rules["titles.$locale"] = 'nullable|string|max:255';
            $rules["contents.$locale"] = 'nullable|string';
            $rules["seo_titles.$locale"] = 'nullable|string|max:255';
            $rules["seo_descriptions.$locale"] = 'nullable|string';
            $rules["seo_keywords.$locale"] = 'nullable|string';
        }
        $rules['is_active'] = 'boolean';
        $rules['sort_order'] = 'integer|min:0';

        $this->validate($rules);

        // Сохраняем динамически
        foreach ($this->locales as $locale => $label) {
            $this->page->{'title_'.$locale} = $this->titles[$locale];
            $this->page->{'content_'.$locale} = $this->contents[$locale];
            $this->page->{'seo_title_'.$locale} = $this->seo_titles[$locale];
            $this->page->{'seo_description_'.$locale} = $this->seo_descriptions[$locale];
            $this->page->{'seo_keywords_'.$locale} = $this->seo_keywords[$locale];
        }

        $this->page->is_active = $this->is_active;
        $this->page->sort_order = $this->sort_order;

        $this->page->save();

        session()->flash('success', 'Страница успешно обновлена!');
        redirect()->route('admin.pages.index');
    }

    #[Layout('admin.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.pages.edit', [
            'locales' => $this->locales,
        ]);
    }
}
