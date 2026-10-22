<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuideAdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PromotionsController;
use App\Http\Controllers\Public\SolutionsController;
use App\Http\Controllers\Public\TipCommentController;
use App\Http\Controllers\Public\TipController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/productos', [CatalogController::class, 'index'])->name('catalog');
Route::get('/producto/{product:slug}', [CatalogController::class, 'show'])->name('product.show');

Route::get('/soluciones', [SolutionsController::class, 'index'])->name('solutions');
Route::get('/tips-ferplec', [TipController::class, 'index'])->name('tips.index');
Route::get('/tips-ferplec/{tip:slug}', [TipController::class, 'show'])->name('tips.show');
Route::post('/tips-ferplec/{tip:slug}/comentarios', [TipCommentController::class, 'store'])->middleware('throttle:3,1')->name('tips.comments.store');

Route::get('/promociones', [PromotionsController::class, 'index'])->name('promotions');
Route::get('/mi-lista', [CartController::class, 'index'])->name('cart');
Route::get('/contacto', [ContactController::class, 'index'])->name('contact');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml'), 200)
        ->header('Content-Type', 'text/plain');
});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/movements', [ProductController::class, 'movements'])->name('products.movements');
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock');
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('tips', GuideAdminController::class)->except('show');
    Route::get('tip-comments', [App\Http\Controllers\Admin\TipCommentController::class, 'index'])->name('tip-comments.index');
    Route::post('tip-comments/{comment}/approve', [App\Http\Controllers\Admin\TipCommentController::class, 'approve'])->name('tip-comments.approve');
    Route::post('tip-comments/{comment}/reject', [App\Http\Controllers\Admin\TipCommentController::class, 'reject'])->name('tip-comments.reject');
    Route::delete('tip-comments/{comment}', [App\Http\Controllers\Admin\TipCommentController::class, 'destroy'])->name('tip-comments.destroy');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
