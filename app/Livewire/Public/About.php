<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Page;
use Artesaos\SEOTools\Facades\SEOTools;

class About extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        $page = Page::where('slug', 'about')->firstOrFail();

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

        return view('livewire.public.about', compact('page'))
            ->title($page->{"title_$locale"});
    }

}
