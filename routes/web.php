<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Admin\Categories\CategoryManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Goods\Create;
use App\Livewire\Admin\Goods\Edit;
use App\Livewire\Admin\Goods\Index;
use App\Livewire\Admin\News\CreateNews;
use App\Livewire\Admin\News\EditNews;
use App\Livewire\Admin\News\NewsList;
use App\Livewire\Admin\ProductTypes\ProductTypeManager;

// Public Components
use App\Livewire\Public\Homepage;
use App\Livewire\Public\ProductCatalog;
use App\Livewire\Public\ProductDetail;
use App\Livewire\Public\NewsIndex;
use App\Livewire\Public\NewsDetail;
use App\Livewire\Public\Cart;
use App\Livewire\Public\Contact;
use App\Livewire\Public\About;

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;

// SEO Routes (outside middleware to avoid duplicate application)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

//Route::middleware(SetLocale::class)->group(function () {

    Route::get('/', Homepage::class)->name('home');

    // Products
    Route::get('/products', ProductCatalog::class)->name('products.index');
    Route::get('/products/search', ProductCatalog::class)->name('products.search');
    Route::get('/products/category/{categoryId}', ProductCatalog::class)->name('products.category');
    Route::get('/product/{product}', ProductDetail::class)->name('products.show');

    // News
    Route::get('/news', NewsIndex::class)->name('news.index');
    Route::get('/news/{news}', NewsDetail::class)->name('news.show');

    // Other Pages
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/about', About::class)->name('about');
    Route::get('/contact', Contact::class)->name('contact');
    Route::post('/contact/send', Contact::class)->name('contact.send');
//});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    /* Categories */
    Route::get('/categories', CategoryManager::class)->name('categories');

    /* ProductTypeManager */
    Route::get('/product-types', ProductTypeManager::class)->name('product-types');

    /* News */
    Route::get('/news', NewsList::class)->name('news.index');
    Route::get('/news-create', CreateNews::class)->name('news.create');
    Route::get('/news/{news}/edit', EditNews::class)->name('news.edit');

    /* Goods */
    Route::get('/goods', Index::class)->name('goods.index');
    Route::get('/goods-create', Create::class)->name('goods.create');
    Route::get('/goods/{good}/edit', Edit::class)->name('goods.edit');

    /* Dashboard */
    Route::get('/dashboard', Dashboard::class)->name('admin-dashboard');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
