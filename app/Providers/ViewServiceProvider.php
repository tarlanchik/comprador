<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share categories with all views
        View::composer('*', function ($view) {
            $categories = Category::whereNull('parent_id')
                ->with(['children' => function ($query) {
                    $query->with('children');
                }])
                ->get();

            $view->with('categories', $categories);
        });

        // Share hreflang data for SEO
        View::composer('*', function ($view) {
            $hreflangs = $this->generateHreflangs();
            $view->with('hreflangs', $hreflangs);
        });
    }

    /**
     * Generate hreflang URLs for current page
     */
    private function generateHreflangs(): array
    {
        $supportedLocales = ['az', 'en', 'ru'];
        $hreflangs = [];

        if (!Route::current()) {
            return $hreflangs;
        }

        $currentRoute = Route::currentRouteName();
        $currentParams = request()->route()->parameters() ?? [];

        foreach ($supportedLocales as $locale) {
            try {
                // Build URL with locale parameter
                $params = array_merge($currentParams, ['lang' => $locale]);
                $url = route($currentRoute, $params);
                $hreflangs[$locale] = $url;
            } catch (\Exception $e) {
                // Fallback: current URL with lang parameter
                $baseUrl = url()->current();
                $separator = parse_url($baseUrl, PHP_URL_QUERY) ? '&' : '?';
                $hreflangs[$locale] = $baseUrl . $separator . 'lang=' . $locale;
            }
        }

        return $hreflangs;
    }
}
