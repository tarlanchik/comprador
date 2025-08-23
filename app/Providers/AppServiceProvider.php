<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register SEO Service
        $this->app->singleton(\App\Services\SeoService::class);

        // Register Sitemap Service
        $this->app->singleton(\App\Services\SitemapService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Schema::defaultStringLength(191);
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Use Bootstrap for pagination
        Paginator::useBootstrapFive();

        // Set default locale
        if (!app()->environment('testing')) {
            $locale = session('locale', config('app.locale', 'az'));
            if (in_array($locale, ['az', 'en', 'ru'])) {
                app()->setLocale($locale);
            }
        }
    }
}
