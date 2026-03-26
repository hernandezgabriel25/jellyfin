<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email') ?: Str::random(10).'@example.com',
            'password' => Hash::make($request->input('password')),
            'uuid' => (string) Str::uuid(),
        ]);
        return redirect()->back()->with('success', 'User created');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted');
    }
}
