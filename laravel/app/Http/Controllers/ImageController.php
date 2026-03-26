<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaItem;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function getImage(Request $request, $itemId, $type)
    {
        $item = MediaItem::findOrFail($itemId);

        $path = match($type) {
            'Primary' => $item->poster_path,
            'Backdrop' => $item->backdrop_path,
            default => null
        };

        if (!$path) {
            // Check parent if it's a season or episode
            if ($item->parent_id) {
                return $this->getImage($request, $item->parent_id, $type);
            }
            return response()->json(['message' => 'Image not found'], 404);
        }

        $url = "https://image.tmdb.org/t/p/original" . $path;

        $response = Http::get($url);

        return response($response->body())
            ->header('Content-Type', $response->header('Content-Type'))
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}
