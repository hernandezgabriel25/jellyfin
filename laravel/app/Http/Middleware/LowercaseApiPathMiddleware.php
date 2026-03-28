<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LowercaseApiPathMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $uri   = $request->getRequestUri();
        $path  = parse_url($uri, PHP_URL_PATH);
        $query = parse_url($uri, PHP_URL_QUERY);

        $lowercasedUri = strtolower($path) . ($query ? '?' . $query : '');

        $request->server->set('REQUEST_URI', $lowercasedUri);

        return $next($request);
    }
}
