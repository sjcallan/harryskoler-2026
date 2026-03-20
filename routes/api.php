<?php

use App\Http\Controllers\Api\GalleryImageController;
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

Route::get('gallery-images', [GalleryImageController::class, 'index']);
