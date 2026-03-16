<?php

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
});

require __DIR__.'/settings.php';
