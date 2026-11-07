<?php

use App\Http\Controllers\Web\MarketingController;
use App\Http\Controllers\Web\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingController::class, 'home'])->name('home');
Route::get('/features', [MarketingController::class, 'features'])->name('features');
Route::get('/modules', [MarketingController::class, 'modules'])->name('modules');
Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
Route::get('/how-it-works', [MarketingController::class, 'howItWorks'])->name('how-it-works');
Route::get('/live-demo', [MarketingController::class, 'liveDemo'])->name('live-demo');
Route::post('/live-demo/login', [MarketingController::class, 'loginAs'])->name('live-demo.login');
Route::get('/integrations', [MarketingController::class, 'integrations'])->name('integrations');
Route::get('/blog', [MarketingController::class, 'blog'])->name('blog');
Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');
Route::get('/legal/{page}', [MarketingController::class, 'legal'])
    ->whereIn('page', ['terms', 'privacy', 'refund', 'cookies'])
    ->name('legal');

Route::get('/store/{business:slug}', [StorefrontController::class, 'show'])->name('storefront.show');
Route::post('/store/{business:slug}/checkout', [StorefrontController::class, 'checkout'])->name('storefront.checkout');
