<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Library;

class LibraryController extends Controller
{
    public function index()
    {
        $libraries = Library::all();
        return view('admin.libraries.index', compact('libraries'));
    }

    public function store(Request $request)
    {
        Library::create($request->only('name', 'type'));
        return redirect()->back()->with('success', 'Library created');
    }

    public function destroy(Library $library)
    {
        $library->delete();
        return redirect()->back()->with('success', 'Library deleted');
    }
}
