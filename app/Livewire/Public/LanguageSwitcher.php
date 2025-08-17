<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class LanguageSwitcher extends Component
{
    public string $currentLocale;
    public array $supportedLocales = [
        'az' => [
            'name' => 'Azərbaycan',
            'native' => 'Azərbaycan',
            'flag' => '🇦🇿',
            'code' => 'az',
            'region' => 'AZ'
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇺🇸',
            'code' => 'en',
            'region' => 'US'
        ],
        'ru' => [
            'name' => 'Русский',
            'native' => 'Русский',
            'flag' => '🇷🇺',
            'code' => 'ru',
            'region' => 'RU'
        ],
    ];

    public array $hreflangs = [];

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
        $this->generateHreflangs();
    }

    public function switchLocale(string $locale)
    {
        if (array_key_exists($locale, $this->supportedLocales)) {
            // Store locale in session
            Session::put('locale', $locale);

            // Set application locale
            app()->setLocale($locale);

            // Update current locale
            $this->currentLocale = $locale;

            // Generate the same URL but with new locale
            $currentRoute = Route::currentRouteName();
            $currentParams = request()->route()->parameters();

            $newUrl = $this->buildLocalizedUrl($currentRoute, $currentParams, $locale);

            // Redirect to the same page with new language
            return redirect()->to($newUrl);
        }
    }

    private function generateHreflangs(): void
    {
        $currentRoute = Route::currentRouteName();
        $currentParams = request()->route()->parameters();

        foreach ($this->supportedLocales as $locale => $data) {
            $this->hreflangs[$locale] = $this->buildLocalizedUrl($currentRoute, $currentParams, $locale);
        }
    }

    private function buildLocalizedUrl(string $routeName, array $params, string $locale): string
    {
        // Store current locale
        $originalLocale = app()->getLocale();

        // Temporarily switch to target locale
        app()->setLocale($locale);

        try {
            // Try to generate route with locale
            $url = route($routeName, array_merge($params, ['lang' => $locale]));
        } catch (\Exception $e) {
            // Fallback to current URL with lang parameter
            $url = request()->url() . '?lang=' . $locale;
        }

        // Restore original locale
        app()->setLocale($originalLocale);

        return $url;
    }

    public function getHreflangsProperty(): array
    {
        return $this->hreflangs;
    }

    public function render()
    {
        return view('livewire.public.language-switcher');
    }
}
