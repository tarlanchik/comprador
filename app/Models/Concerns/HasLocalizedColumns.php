<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasLocalizedColumns
{
    protected function localized(string $base): mixed
    {
        $locale = app()->getLocale();
        $column = "{$base}_{$locale}";
        return $this->{$column};
    }

    // Теперь accessor'ы для конкретных полей
    protected function title(): Attribute
    {
        return Attribute::get(fn () => $this->localized('title'));
    }

    protected function content(): Attribute
    {
        return Attribute::get(fn () => $this->localized('content'));
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => $this->localized('name'));
    }

    protected function description(): Attribute
    {
        return Attribute::get(fn () => $this->localized('description'));
    }

    protected function keywords(): Attribute
    {
        return Attribute::get(fn () => $this->localized('keywords'));
    }
}
