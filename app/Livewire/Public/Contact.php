<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Page;
use Artesaos\SEOTools\Facades\SEOTools;
use Livewire\Attributes\Layout;

class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:255',
        'subject' => 'required|min:5|max:255',
        'message' => 'required|min:10|max:1000',
    ];

    public function submitContact(): void
    {
        $this->validate();

        // Here you would typically send an email or save to database
        // For now, we'll just show a success message

        session()->flash('success', 'Thank you for your message! We will get back to you soon.');

        $this->reset(['name', 'email', 'subject', 'message']);
    }


    #[Layout('layouts.public')]
    public function render()
    {
        $page = Page::where('slug', 'contact')->firstOrFail();

        $locale = app()->getLocale();

        // SEO
        SEOTools::setTitle($page->{"seo_title_$locale"} ?? $page->{"title_$locale"});
        SEOTools::setDescription($page->{"seo_description_$locale"} ?? '');
        SEOTools::metatags()->setKeywords(
            explode(',', $page->{"seo_keywords_$locale"} ?? '')
        );

        SEOTools::opengraph()->setTitle($page->{"seo_title_$locale"} ?? $page->{"title_$locale"});
        SEOTools::opengraph()->setDescription($page->{"seo_description_$locale"} ?? '');
        SEOTools::opengraph()->setUrl(url()->current());

        return view('livewire.admin.pages.contact', compact('page'))
            ->title($page->{"title_$locale"});
    }

}
