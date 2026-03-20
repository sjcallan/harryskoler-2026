<?php

use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PressEventController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\RadioAirplayController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::inertia('/', 'Index')->name('home');

Route::get('/album/{slug}', function (string $slug) {
    return Inertia::render('Album', ['slug' => $slug]);
})->where('slug', '[a-z0-9\-]+')->name('album.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'admin/Dashboard')->name('dashboard');
    Route::inertia('news', 'admin/News')->name('news');
    Route::inertia('reviews', 'admin/Reviews')->name('reviews');
    Route::inertia('radio-airplay', 'admin/RadioAirplay')->name('radio-airplay');
    Route::inertia('press', 'admin/Press')->name('press');
    Route::inertia('quotes', 'admin/Quotes')->name('quotes');
    Route::inertia('users', 'admin/Users')->name('users');
});

Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {
    Route::post('news', [NewsController::class, 'store']);
    Route::post('news/{news}', [NewsController::class, 'update']);
    Route::delete('news/{news}', [NewsController::class, 'destroy']);

    Route::post('reviews', [ReviewController::class, 'store']);
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
});

require __DIR__.'/settings.php';
