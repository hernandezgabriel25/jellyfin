<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function authenticateByName(Request $request)
    {
        $username = $request->input('Username');
        $password = $request->input('Pw');

        $user = User::where('name', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'User' => $this->getUserDto($user),
            'AccessToken' => $user->uuid,
            'SessionId' => Str::random(32),
            'ServerId' => 'laravel-server',
        ]);
    }

    public function getPublicUsers()
    {
        $users = User::all()->map(fn($user) => [
            'Name' => $user->name,
            'Id' => $user->uuid,
            'HasPassword' => true,
            'EnableAutoLogin' => false,
        ]);

        return response()->json($users);
    }

    public function getCurrentUser(Request $request)
    {
        return response()->json($this->getUserDto($request->user()));
    }

    public function getUserById(Request $request, $userId)
    {
        $user = User::where('uuid', $userId)->firstOrFail();
        return response()->json($this->getUserDto($user));
    }

    private function getUserDto($user)
    {
        return [
            'Name' => $user->name,
            'Id' => $user->uuid,
            'HasPassword' => true,
            'Configuration' => [
                'PlayDefaultAudioTrack' => true,
                'DisplayMissingEpisodes' => false,
            ],
            'Policy' => [
                'IsAdministrator' => true,
                'IsDisabled' => false,
            ],
        ];
    }
}
