<?php

use App\Livewire\Api\Index;
use App\Livewire\Api\Show;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/apis', Index::class)->name('apis.index');
    Route::get('/apis/{id}', Show::class)->name('apis.show');
});

require __DIR__.'/settings.php';
