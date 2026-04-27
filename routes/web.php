<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GalleryImageController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PressEventController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\RadioAirplayController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Index')->name('home');

Route::get('/album/{slug}', function (string $slug) {
    return Inertia::render('Album', ['slug' => $slug]);
})->where('slug', '[a-z0-9\-]+')->name('album.show');

/*
|--------------------------------------------------------------------------
| Legacy /music → /album 301 redirects
|--------------------------------------------------------------------------
|
| Preserves SEO value from the previous site structure where albums lived
| under /music/{slug}. Both the index and individual album URLs are
| permanently redirected to their new /album counterparts.
|
*/
Route::permanentRedirect('/music', '/album');
Route::permanentRedirect('/music/{slug}', '/album/{slug}')
    ->where('slug', '[a-z0-9\-]+');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'admin/Dashboard')->name('dashboard');
    Route::inertia('news', 'admin/News')->name('news');
    Route::inertia('reviews', 'admin/Reviews')->name('reviews');
    Route::inertia('radio-airplay', 'admin/RadioAirplay')->name('radio-airplay');
    Route::inertia('press', 'admin/Press')->name('press');
    Route::inertia('quotes', 'admin/Quotes')->name('quotes');
    Route::inertia('users', 'admin/Users')->name('users');
    Route::inertia('gallery', 'admin/Gallery')->name('gallery');
});

/*
|--------------------------------------------------------------------------
| Public JSON API (read-only)
|--------------------------------------------------------------------------
|
| These endpoints are registered in `web.php` on purpose: they need access
| to the session (cookie) so the controllers can tell whether the viewer
| is authenticated or has enabled preview-mode. Session-less API routes
| would always return public (published-only) content.
|
*/
Route::prefix('api')->group(function () {
    Route::get('news', [NewsController::class, 'index']);
    Route::get('news/{news}', [NewsController::class, 'show']);

    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('reviews/{review}', [ReviewController::class, 'show']);

    Route::get('radio-airplays', [RadioAirplayController::class, 'index']);
    Route::get('radio-airplays/{radio_airplay}', [RadioAirplayController::class, 'show']);

    Route::get('press-events', [PressEventController::class, 'index']);
    Route::get('press-events/{press_event}', [PressEventController::class, 'show']);

    Route::get('quotes', [QuoteController::class, 'index']);
    Route::get('quotes/active', [QuoteController::class, 'active']);
    Route::get('quotes/{quote}', [QuoteController::class, 'show']);

    Route::get('gallery-images', [GalleryImageController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Authenticated admin mutation endpoints
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {
    Route::post('news', [NewsController::class, 'store']);
    Route::post('news/{news}', [NewsController::class, 'update']);
    Route::delete('news/{news}', [NewsController::class, 'destroy']);

    Route::post('reviews', [ReviewController::class, 'store']);
    Route::post('reviews/reorder', [ReviewController::class, 'reorder']);
    Route::post('reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);

    Route::post('radio-airplays', [RadioAirplayController::class, 'store']);
    Route::post('radio-airplays/reorder', [RadioAirplayController::class, 'reorder']);
    Route::post('radio-airplays/{radio_airplay}', [RadioAirplayController::class, 'update']);
    Route::delete('radio-airplays/{radio_airplay}', [RadioAirplayController::class, 'destroy']);

    Route::post('press-events', [PressEventController::class, 'store']);
    Route::post('press-events/{press_event}', [PressEventController::class, 'update']);
    Route::delete('press-events/{press_event}', [PressEventController::class, 'destroy']);

    Route::post('quotes', [QuoteController::class, 'store']);
    Route::post('quotes/{quote}', [QuoteController::class, 'update']);
    Route::delete('quotes/{quote}', [QuoteController::class, 'destroy']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::post('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/recent', [DashboardController::class, 'recent']);
    Route::get('dashboard/storage', [DashboardController::class, 'storage']);

    Route::post('gallery-images', [GalleryImageController::class, 'store']);
    Route::post('gallery-images/reorder', [GalleryImageController::class, 'reorder']);
    Route::post('gallery-images/{gallery_image}', [GalleryImageController::class, 'update']);
    Route::delete('gallery-images/{gallery_image}', [GalleryImageController::class, 'destroy']);
});

require __DIR__.'/settings.php';
