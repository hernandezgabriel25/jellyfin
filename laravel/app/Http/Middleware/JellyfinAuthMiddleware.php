<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class JellyfinAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->query('Token') ?: $request->query('api_key') ?: $request->query('ApiKey');

        $authHeader = $request->header('X-Emby-Authorization') ?: $request->header('Authorization');

        if ($authHeader) {
            $parts = $this->parseAuthHeader($authHeader);
            if (isset($parts['Token'])) {
                $token = $parts['Token'];
            }
        }

        if ($token) {
            $user = User::where('uuid', $token)->first();
            if ($user) {
                auth()->login($user);
            }
        }

        return $next($request);
    }

    private function parseAuthHeader($header)
    {
        $header = str_replace(['MediaBrowser ', 'Emby '], '', $header);
        $parts = [];
        $segments = explode(',', $header);
        foreach ($segments as $segment) {
            $kv = explode('=', $segment);
            if (count($kv) === 2) {
                $parts[trim($kv[0])] = trim($kv[1], ' "');
            }
        }
        return $parts;
    }
}
