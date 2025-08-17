<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOTools;

class SeoService
{
    public function setMeta(string $title, ?string $description = null, $keywords = null): void
    {
        SEOTools::setTitle($title);

        if ($description) {
            SEOTools::setDescription($description);
        }

        if ($keywords) {
            if (is_array($keywords)) {
                $keywords = implode(', ', $keywords);
            }
            SEOTools::metatags()->addMeta('keywords', $keywords, 'name');
        }
    }

    /**
     * Set homepage SEO.
     * Accepts: null | array | object | string
     */
    public function setHomepageSeo($data = null): void
    {
        if ($data === null) {
            // Default values — istəsən config-dən götür
            $title = config('app.name', 'Saytın adı');
            $description = config('seo.home.description', 'Saytın ana səhifəsinin təsviri');
            $keywords = config('seo.home.keywords', ['ana səhifə', 'shop']);
        } elseif (is_array($data)) {
            $title = $data['title'] ?? config('app.name');
            $description = $data['description'] ?? null;
            $keywords = $data['keywords'] ?? null;
        } elseif (is_object($data)) {
            $title = $data->title ?? config('app.name');
            $description = $data->description ?? null;
            $keywords = $data->keywords ?? null;
        } else {
            $title = (string) $data;
            $description = null;
            $keywords = null;
        }

        $this->setMeta($title, $description, $keywords);
    }
}
