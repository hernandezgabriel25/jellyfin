<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function getSystemInfo()
    {
        return response()->json([
            'Name' => 'Laravel Jellyfin',
            'Version' => '10.11.0',
            'Id' => 'laravel-server-id',
            'StartupWizardCompleted' => true,
            'OperatingSystem' => 'Linux',
            'CanSelfRestart' => false,
            'CanLaunchWebBrowser' => false,
        ]);
    }

    public function getPublicSystemInfo()
    {
        return response()->json([
            'Name' => 'Laravel Jellyfin',
            'Version' => '10.11.0',
            'Id' => 'laravel-server-id',
            'LocalAddress' => 'http://localhost:8000',
            'StartupWizardCompleted' => true,
        ]);
    }

    public function ping()
    {
        return response()->json('Laravel Jellyfin');
    }
}
