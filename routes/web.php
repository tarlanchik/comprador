<?php

//use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Goods\Create;
use App\Livewire\Admin\Goods\Edit;
use App\Livewire\Admin\Goods\Index;
use App\Livewire\Admin\GoodsList;
use App\Livewire\Admin\ProductTypeManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/categories', CategoryManager::class)->name('categories');

    /* Goods */
    Route::get('/goods', Index::class)->name('goods.index');
    Route::get('/goods-create', Create::class)->name('goods.create');
    Route::get('/goods/{good}/edit', Edit::class)->name('goods.edit');
    Route::get('/goods-index-page', function() {
        return view('livewire.admin.pages.goods.index');
    })->name('goods-index');
    Route::get('/goods-create-page', function() {
        return view('livewire.admin.pages.goods.edit-create');
    })->name('goods-create');

    Route::get('/goods-edit-page', function() {
        return view('livewire.admin.pages.goods.edit-create');
    })->name('goods-edit');



    Route::get('/product-types', ProductTypeManager::class)->name('product-types');

    Route::get('/dashboard', function() {
        return view('livewire.admin.pages.dashboard-page');
    })->name('admin-dashboard');

    /*
    Route::get('/goods-manager', function() {
            return view('livewire.admin.pages.goods-manager-page');
        })->name('admin-goods-manar');


        Route::get('/goods-manager', function() {
            return view('livewire.admin.pages.goods-manager-page');
        })->name('admin-goods-manager');
    */
    Route::get('/categories-manager', function() {
        return view('livewire.admin.pages.categories-page');
    })->name('admin-categories');

    Route::get('/product-types-manager', function() {
        return view('livewire.admin.pages.product-types-page');
    })->name('admin-product-types');



});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
