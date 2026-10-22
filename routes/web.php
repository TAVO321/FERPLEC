<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TipAdminController;
use App\Http\Controllers\Admin\TipCommentController as AdminTipCommentController;
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
Route::post('/tips-ferplec/{tip:slug}/comentarios', [TipCommentController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('tips.comments.store');

Route::get('/promociones', [PromotionsController::class, 'index'])->name('promotions');
Route::get('/mi-lista', [CartController::class, 'index'])->name('cart');
Route::get('/contacto', [ContactController::class, 'index'])->name('contact');

Route::get('/sitemap.xml', fn () => response()
    ->view('sitemap')
    ->header('Content-Type', 'application/xml; charset=utf-8'))
    ->name('sitemap');

Route::get('/robots.txt', fn () => response(
    "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml'),
    200
)->header('Content-Type', 'text/plain; charset=utf-8'));

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

// El cierre de sesión solo exige estar autenticado: cualquier usuario
// autenticado debe poder cerrar su propia sesión.
Route::middleware('auth')->post('/admin/logout', [LoginController::class, 'destroy'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/movements', [ProductController::class, 'movements'])->name('products.movements');
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock');
    Route::resource('products', ProductController::class)->except('show');

    Route::resource('categories', CategoryController::class)->except('show');

    Route::resource('tips', TipAdminController::class)->except('show');

    Route::get('tip-comments', [AdminTipCommentController::class, 'index'])->name('tip-comments.index');
    Route::post('tip-comments/{comment}/approve', [AdminTipCommentController::class, 'approve'])->name('tip-comments.approve');
    Route::post('tip-comments/{comment}/reject', [AdminTipCommentController::class, 'reject'])->name('tip-comments.reject');
    Route::delete('tip-comments/{comment}', [AdminTipCommentController::class, 'destroy'])->name('tip-comments.destroy');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
