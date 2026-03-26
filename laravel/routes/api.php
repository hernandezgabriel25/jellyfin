<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MediaInfoController;
use App\Http\Controllers\StubController;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\JellyfinAuthMiddleware;

Route::prefix('api')->group(function() {
    Route::post('/Users/AuthenticateByName', [UserController::class, 'authenticateByName']);
    Route::get('/System/Info/Public', [SystemController::class, 'getPublicSystemInfo']);
    Route::get('/System/Ping', [SystemController::class, 'ping']);
    Route::post('/System/Ping', [SystemController::class, 'ping']);

    Route::middleware([JellyfinAuthMiddleware::class])->group(function () {
        Route::get('/Users/Me', [UserController::class, 'getCurrentUser']);
        Route::get('/Users/{userId}', [UserController::class, 'getUserById']);
        Route::get('/System/Info', [SystemController::class, 'getSystemInfo']);

        Route::get('/Users/{userId}/Views', [ItemController::class, 'getViews']);
        Route::get('/Items', [ItemController::class, 'getItems']);
        Route::get('/Items/{itemId}', [ItemController::class, 'getItemById']);

        Route::get('/Items/{itemId}/Images/{type}', [ImageController::class, 'getImage']);
        Route::get('/Items/{itemId}/Images/{type}/{index}', [ImageController::class, 'getImage']);

        Route::get('/Items/{itemId}/PlaybackInfo', [MediaInfoController::class, 'getPlaybackInfo']);
        Route::post('/Items/{itemId}/PlaybackInfo', [MediaInfoController::class, 'postPlaybackInfo']);

        // Stubs
        Route::get('/DisplayPreferences/{id}', [StubController::class, 'getDisplayPreferences']);
        Route::get('/Notifications/{id}', [StubController::class, 'getNotifications']);
        Route::get('/Localization/Capabilities', [StubController::class, 'getLocalizationCapabilities']);
        Route::get('/ScheduledTasks', [StubController::class, 'getScheduledTasks']);
        Route::get('/Plugins/SecurityInfo', [StubController::class, 'getPluginSecurityInfo']);
    });
});
