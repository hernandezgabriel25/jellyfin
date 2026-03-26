<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class TmdbService
{
    private $apiKey;
    private $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct()
    {
        $this->apiKey = Setting::where('key', 'tmdb_api_key')->value('value');
    }

    public function searchMovie($query)
    {
        if (!$this->apiKey) return [];

        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'query' => $query,
        ]);

        return $response->json()['results'] ?? [];
    }

    public function searchTv($query)
    {
        if (!$this->apiKey) return [];

        $response = Http::get("{$this->baseUrl}/search/tv", [
            'api_key' => $this->apiKey,
            'query' => $query,
        ]);

        return $response->json()['results'] ?? [];
    }

    public function getMovieDetails($id)
    {
        if (!$this->apiKey) return null;

        $response = Http::get("{$this->baseUrl}/movie/{$id}", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function getTvDetails($id)
    {
        if (!$this->apiKey) return null;

        $response = Http::get("{$this->baseUrl}/tv/{$id}", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }
}
