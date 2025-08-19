<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProfanityFilter implements Rule
{
    private $profaneWords = [
        'spam', 'stupid', 'idiot', 'hate', 'kill'
        // Add more words or load from database/config
    ];

    public function passes($attribute, $value)
    {
        $content = strtolower($value);

        foreach ($this->profaneWords as $word) {
            if (strpos($content, strtolower($word)) !== false) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute contains inappropriate language.';
    }
}
