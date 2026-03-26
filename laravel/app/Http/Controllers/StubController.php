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
}
