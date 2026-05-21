<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrantController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/grants');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/grants', [GrantController::class, 'index']);
    Route::post('/grants/search', [GrantController::class, 'search']);
    Route::get('/grants/{id}', [GrantController::class, 'show']);

    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});

require __DIR__.'/auth.php';
