<?php
//php artisan seo:generate-sitemap
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Goods;
use App\Models\News;
use App\Models\Category;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:generate-sitemap';
    protected $description = 'Generate sitemap.xml';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        // Главная
        $sitemap->add(Url::create(route('home', 'az'))->setPriority(1.0));

        // Категории
        foreach (Category::all() as $category) {
            $sitemap->add(Url::create(route('catalog.category', ['az', $category->slug]))
                ->setPriority(0.8)
                ->setLastModificationDate($category->updated_at));
        }

        // Товары
        foreach (Goods::all() as $product) {
            $sitemap->add(Url::create(route('product.show', ['az', $product->slug]))
                ->setPriority(0.9)
                ->setLastModificationDate($product->updated_at));
        }

        // Новости
        foreach (News::all() as $news) {
            $sitemap->add(Url::create(route('news.show', ['az', $news->slug]))
                ->setPriority(0.7)
                ->setLastModificationDate($news->updated_at));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
