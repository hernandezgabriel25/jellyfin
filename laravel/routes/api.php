<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MediaInfoController;
use App\Http\Controllers\StubController;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\JellyfinAuthMiddleware;

// No-auth routes (path will already be lowercased by LowercaseApiPathMiddleware)
Route::post('/users/authenticatebyname', [UserController::class, 'authenticateByName']);
Route::get('/system/info/public', [SystemController::class, 'getPublicSystemInfo']);
Route::get('/system/ping', [SystemController::class, 'ping']);
Route::post('/system/ping', [SystemController::class, 'ping']);

// Public user list for login screen
Route::get('/users/public', [UserController::class, 'getPublicUsers']);

// Startup wizard stubs
Route::get('/startup/configuration', [StubController::class, 'getStartupConfiguration']);
Route::post('/startup/configuration', [StubController::class, 'updateStartupConfiguration']);
Route::get('/startup/user', [StubController::class, 'getStartupUser']);
Route::post('/startup/user', [StubController::class, 'updateStartupUser']);
Route::post('/startup/complete', [StubController::class, 'completeStartup']);
Route::get('/startup/remoteaccess', [StubController::class, 'getStartupRemoteAccess']);
Route::post('/startup/remoteaccess', [StubController::class, 'updateStartupRemoteAccess']);

// QuickConnect stubs (disabled)
Route::get('/quickconnect/enabled', [StubController::class, 'quickConnectEnabled']);
Route::get('/quickconnect/initiate', [StubController::class, 'quickConnectInitiate']);
Route::get('/quickconnect/connect', [StubController::class, 'quickConnectConnect']);

// Branding stubs
Route::get('/branding/configuration', [StubController::class, 'getBrandingConfiguration']);

Route::middleware([JellyfinAuthMiddleware::class])->group(function () {
    Route::get('/users/me', [UserController::class, 'getCurrentUser']);
    Route::get('/users/{userId}', [UserController::class, 'getUserById']);
    Route::get('/system/info', [SystemController::class, 'getSystemInfo']);
    Route::get('/system/endpoint', [StubController::class, 'getEndpoint']);

    // Sessions
    Route::get('/sessions', [StubController::class, 'getSessions']);
    Route::post('/sessions/capabilities', [StubController::class, 'reportCapabilities']);
    Route::post('/sessions/capabilities/full', [StubController::class, 'reportCapabilities']);

    Route::get('/users/{userId}/views', [ItemController::class, 'getViews']);
    Route::get('/items', [ItemController::class, 'getItems']);
    Route::get('/items/{itemId}', [ItemController::class, 'getItemById']);

    Route::get('/items/{itemId}/images/{type}', [ImageController::class, 'getImage']);
    Route::get('/items/{itemId}/images/{type}/{index}', [ImageController::class, 'getImage']);

    Route::get('/items/{itemId}/playbackinfo', [MediaInfoController::class, 'getPlaybackInfo']);
    Route::post('/items/{itemId}/playbackinfo', [MediaInfoController::class, 'postPlaybackInfo']);

    // Stubs
    Route::get('/displaypreferences/{id}', [StubController::class, 'getDisplayPreferences']);
    Route::get('/notifications/{id}', [StubController::class, 'getNotifications']);
    Route::get('/localization/capabilities', [StubController::class, 'getLocalizationCapabilities']);
    Route::get('/scheduledtasks', [StubController::class, 'getScheduledTasks']);
    Route::get('/plugins/securityinfo', [StubController::class, 'getPluginSecurityInfo']);
});
