<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Goods;
use App\Models\News;
use Illuminate\Support\Facades\Response;

class SitemapService
{
    private array $supportedLocales = ['az', 'en', 'ru'];

    public function generateSitemap(): \Illuminate\Http\Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // Homepage
        $xml .= $this->addUrl(route('home'), now(), 'daily', '1.0', $this->getHomepageAlternates());

        // Categories
        $categories = Category::all();
        foreach ($categories as $category) {
            $xml .= $this->addUrl(
                route('products.category', $category->id),
                $category->updated_at,
                'weekly',
                '0.8',
                $this->getCategoryAlternates($category)
            );
        }

        // Products
        $products = Goods::where('count', '>', 0)->get();
        foreach ($products as $product) {
            $xml .= $this->addUrl(
                route('products.show', $product->id),
                $product->updated_at,
                'weekly',
                '0.7',
                $this->getProductAlternates($product)
            );
        }

        // News
        $news = News::all();
        foreach ($news as $newsItem) {
            $xml .= $this->addUrl(
                route('news.show', $newsItem->id),
                $newsItem->updated_at,
                'monthly',
                '0.6',
                $this->getNewsAlternates($newsItem)
            );
        }

        // Static pages
        $xml .= $this->addUrl(route('products.index'), now(), 'daily', '0.9', $this->getStaticPageAlternates('products.index'));
        $xml .= $this->addUrl(route('news.index'), now(), 'daily', '0.7', $this->getStaticPageAlternates('news.index'));
        $xml .= $this->addUrl(route('about'), now(), 'monthly', '0.5', $this->getStaticPageAlternates('about'));
        $xml .= $this->addUrl(route('contact'), now(), 'monthly', '0.5', $this->getStaticPageAlternates('contact'));

        $xml .= '</urlset>';

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    private function addUrl(string $loc, $lastmod, string $changefreq, string $priority, array $alternates = []): string
    {
        $xml = '<url>';
        $xml .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        $xml .= '<lastmod>' . $lastmod->toW3cString() . '</lastmod>';
        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';

        // Add hreflang alternates
        foreach ($alternates as $locale => $url) {
            $xml .= '<xhtml:link rel="alternate" hreflang="' . $locale . '" href="' . htmlspecialchars($url) . '" />';
        }

        $xml .= '</url>';
        return $xml;
    }

    private function getHomepageAlternates(): array
    {
        $alternates = [];
        foreach ($this->supportedLocales as $locale) {
            $alternates[$locale] = route('home', ['lang' => $locale]);
        }
        return $alternates;
    }

    private function getCategoryAlternates(Category $category): array
    {
        $alternates = [];
        foreach ($this->supportedLocales as $locale) {
            $alternates[$locale] = route('products.category', ['categoryId' => $category->id, 'lang' => $locale]);
        }
        return $alternates;
    }

    private function getProductAlternates(Goods $product): array
    {
        $alternates = [];
        foreach ($this->supportedLocales as $locale) {
            $alternates[$locale] = route('products.show', ['product' => $product->id, 'lang' => $locale]);
        }
        return $alternates;
    }

    private function getNewsAlternates(News $news): array
    {
        $alternates = [];
        foreach ($this->supportedLocales as $locale) {
            $alternates[$locale] = route('news.show', ['news' => $news->id, 'lang' => $locale]);
        }
        return $alternates;
    }

    private function getStaticPageAlternates(string $routeName): array
    {
        $alternates = [];
        foreach ($this->supportedLocales as $locale) {
            $alternates[$locale] = route($routeName, ['lang' => $locale]);
        }
        return $alternates;
    }
}
