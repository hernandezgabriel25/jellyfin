<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StubController extends Controller
{
    public function getDisplayPreferences()
    {
        return response()->json([
            'Id' => 'default',
            'ViewType' => 'Poster',
            'SortBy' => 'SortName',
            'SortOrder' => 'Ascending',
        ]);
    }

    public function getNotifications()
    {
        return response()->json([
            'Notifications' => [],
            'TotalRecordCount' => 0,
        ]);
    }

    public function getLocalizationCapabilities()
    {
        return response()->json([]);
    }

    public function getScheduledTasks()
    {
        return response()->json([]);
    }

    public function getPluginSecurityInfo()
    {
        return response()->json(['IsPluginSecurityInfoValid' => true]);
    }

    // Startup wizard stubs — return "already configured" so the client skips setup
    public function getStartupConfiguration()
    {
        return response()->json([
            'UICulture' => 'en-US',
            'MetadataCountryCode' => 'US',
            'PreferredMetadataLanguage' => 'en',
        ]);
    }

    public function updateStartupConfiguration()
    {
        return response()->json(null, 204);
    }

    public function getStartupUser()
    {
        return response()->json([
            'Name' => 'Admin',
            'Password' => '',
            'PasswordConfirm' => '',
        ]);
    }

    public function updateStartupUser()
    {
        return response()->json(null, 204);
    }

    public function completeStartup()
    {
        return response()->json(null, 204);
    }

    public function getStartupRemoteAccess()
    {
        return response()->json([
            'EnableRemoteAccess' => true,
            'EnableAutomaticPortMapping' => false,
        ]);
    }

    public function updateStartupRemoteAccess()
    {
        return response()->json(null, 204);
    }

    // QuickConnect stubs — disabled
    public function quickConnectEnabled()
    {
        return response()->json(false);
    }

    public function quickConnectInitiate()
    {
        return response()->json(['Error' => 'QuickConnect is not active'], 400);
    }

    public function quickConnectConnect()
    {
        return response()->json(['Error' => 'QuickConnect is not active'], 400);
    }

    // Branding stubs
    public function getBrandingConfiguration()
    {
        return response()->json([
            'LoginDisclaimer' => null,
            'CustomCss' => null,
            'SplashscreenEnabled' => false,
        ]);
    }

    // System/Endpoint
    public function getEndpoint()
    {
        return response()->json([
            'IsInNetwork' => true,
        ]);
    }

    // Sessions stubs
    public function getSessions()
    {
        return response()->json([]);
    }

    public function reportCapabilities()
    {
        return response()->json(null, 204);
    }
}
