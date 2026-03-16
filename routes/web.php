<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Index')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('news', 'News')->name('news');
    Route::inertia('reviews', 'Reviews')->name('reviews');
    Route::inertia('radio-airplay', 'RadioAirplay')->name('radio-airplay');
});

require __DIR__.'/settings.php';
