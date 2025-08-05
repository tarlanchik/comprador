<?php

// use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\Categories\CategoryManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Goods\Create;
use App\Livewire\Admin\Goods\Edit;
use App\Livewire\Admin\Goods\Index;
use App\Livewire\Admin\News\CreateNews;
use App\Livewire\Admin\News\EditNews;
use App\Livewire\Admin\News\NewsList;
use App\Livewire\Admin\ProductTypes\ProductTypeManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    /* Categories */
    Route::get('/categories', CategoryManager::class)->name('categories');
    /* ProductTypeManager */
    Route::get('/product-types', ProductTypeManager::class)->name('product-types');
    /* News */
    Route::get('/news', NewsList::class)->name('news.index');
    Route::get('/news-create', CreateNews::class)->name('news.create');
    Route::get('/news/{news}/news', EditNews::class)->name('news.edit');
    /* Goods */
    Route::get('/goods', Index::class)->name('goods.index');
    Route::get('/goods-create', Create::class)->name('goods.create');
    Route::get('/goods/{good}/edit', Edit::class)->name('goods.edit');
    /* Dashboard */
    Route::get('/dashboard', Dashboard::class)->name('admin-dashboard');

 });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
