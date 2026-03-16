<?php

use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PressEventController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\RadioAirplayController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('news', [NewsController::class, 'store']);
    Route::post('news/{news}', [NewsController::class, 'update']);
    Route::delete('news/{news}', [NewsController::class, 'destroy']);

    Route::post('reviews', [ReviewController::class, 'store']);
    Route::post('reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);

    Route::post('radio-airplays', [RadioAirplayController::class, 'store']);
    Route::post('radio-airplays/{radio_airplay}', [RadioAirplayController::class, 'update']);
    Route::delete('radio-airplays/{radio_airplay}', [RadioAirplayController::class, 'destroy']);

    Route::post('press-events', [PressEventController::class, 'store']);
    Route::post('press-events/{press_event}', [PressEventController::class, 'update']);
    Route::delete('press-events/{press_event}', [PressEventController::class, 'destroy']);

    Route::post('quotes', [QuoteController::class, 'store']);
    Route::post('quotes/{quote}', [QuoteController::class, 'update']);
    Route::delete('quotes/{quote}', [QuoteController::class, 'destroy']);
});
