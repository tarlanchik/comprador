<?php

namespace App\Support;
use Illuminate\Support\Facades\DB;

class Localized
{
    public static function select(string $base): \Illuminate\Database\Query\Expression
    {
        $loc = app()->getLocale();
        $fb = config('app.short_locales', 'az');
        $a = $base . '_' . $loc;
        $b = $base . '_' . $fb;
// COALESCE на 2 шага + последний безопасный
        return DB::raw("COALESCE($a, $b, $a) AS $base");
    }
}
