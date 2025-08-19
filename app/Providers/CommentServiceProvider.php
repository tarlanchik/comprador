<?php

namespace App\Providers;

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\ProfanityFilter;
use App\Services\CommentService;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(CommentService::class);
    }

    public function boot(): void
    {
        Validator::extend('profanity_filter', function ($attribute, $value, $parameters, $validator) {
            return (new ProfanityFilter())->passes($attribute, $value);
        });

        Validator::replacer('profanity_filter', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute contains inappropriate language.');
        });
    }
}
