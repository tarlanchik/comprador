<?php

return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => env('APP_LOCALE', 'az'),
    'main_lang' => 'az',
    'locales' => [
        'az' => 'Azərbaycan',
        'ru' => 'Русский',
        'en' => 'English',
    ],
    'short_locales' => ['az','ru','en'],
    'supported_locales' => [
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
    ],
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    //'providers' => [
    //    App\Providers\CommentServiceProvider::class,
    //],
        'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];
