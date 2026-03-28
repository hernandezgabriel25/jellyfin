<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    return redirect()->route('admin.libraries.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/libraries', [LibraryController::class, 'index'])->name('libraries.index');
    Route::post('/libraries', [LibraryController::class, 'store'])->name('libraries.store');
    Route::delete('/libraries/{library}', [LibraryController::class, 'destroy'])->name('libraries.destroy');

    Route::get('/libraries/{library}/media/{parent?}', [MediaItemController::class, 'index'])->name('media.index');
    Route::post('/libraries/{library}/media/{parent?}', [MediaItemController::class, 'store'])->name('media.store');
    Route::get('/media/search', [MediaItemController::class, 'search'])->name('media.search');
    Route::get('/media/{item}/edit', [MediaItemController::class, 'edit'])->name('media.edit');
    Route::put('/media/{item}', [MediaItemController::class, 'update'])->name('media.update');
    Route::delete('/media/{item}', [MediaItemController::class, 'destroy'])->name('media.destroy');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
