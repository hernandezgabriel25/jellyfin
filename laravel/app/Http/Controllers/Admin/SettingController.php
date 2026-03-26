<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $tmdb_api_key = Setting::where('key', 'tmdb_api_key')->value('value');
        return view('admin.settings.index', compact('tmdb_api_key'));
    }

    public function update(Request $request)
    {
        Setting::updateOrCreate(['key' => 'tmdb_api_key'], ['value' => $request->input('tmdb_api_key')]);
        return redirect()->back()->with('success', 'Settings updated');
    }
}
