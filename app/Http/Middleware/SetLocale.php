<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // ⚡ Должно совпадать с {lang?} в web.php
        $locale = $request->route('lang');

        $supportedLocales = config('app.short_locales', ['az', 'en', 'ru']);
        $defaultLocale = config('app.main_lang', 'az');

        if ($locale && in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            App::setLocale($defaultLocale);
            Session::put('locale', $defaultLocale);
        }

        return $next($request);
    }
}
