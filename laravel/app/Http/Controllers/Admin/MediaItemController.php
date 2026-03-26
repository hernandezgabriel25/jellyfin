<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaItem;
use App\Models\Library;
use App\Services\TmdbService;
use Illuminate\Support\Str;

class MediaItemController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function index(Library $library, MediaItem $parent = null)
    {
        $query = $library->mediaItems();
        if ($parent) {
            $query->where('parent_id', $parent->id);
        } else {
            $query->whereNull('parent_id');
        }
        $items = $query->get();
        return view('admin.media.index', compact('library', 'items', 'parent'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type'); // movie or tv

        if ($type == 'movie') {
            $results = $this->tmdb->searchMovie($query);
        } else {
            $results = $this->tmdb->searchTv($query);
        }

        return response()->json($results);
    }

    public function store(Request $request, Library $library, MediaItem $parent = null)
    {
        $tmdb_id = $request->input('tmdb_id');
        $type = $request->input('type'); // movie or series or season or episode

        if ($type == 'movie') {
            $details = $this->tmdb->getMovieDetails($tmdb_id);
            $item = MediaItem::create([
                'id' => (string) Str::uuid(),
                'library_id' => $library->id,
                'type' => 'movie',
                'name' => $details['title'],
                'overview' => $details['overview'],
                'tmdb_id' => $tmdb_id,
                'imdb_id' => $details['imdb_id'] ?? null,
                'production_year' => substr($details['release_date'] ?? '', 0, 4),
                'poster_path' => $details['poster_path'],
                'backdrop_path' => $details['backdrop_path'],
            ]);
        } else {
            $details = $this->tmdb->getTvDetails($tmdb_id);
            $item = MediaItem::create([
                'id' => (string) Str::uuid(),
                'library_id' => $library->id,
                'type' => 'series',
                'name' => $details['name'],
                'overview' => $details['overview'],
                'tmdb_id' => $tmdb_id,
                'production_year' => substr($details['first_air_date'] ?? '', 0, 4),
                'poster_path' => $details['poster_path'],
                'backdrop_path' => $details['backdrop_path'],
            ]);
            // Logic for seasons and episodes could be added here
        } elseif ($type == 'season') {
            $item = MediaItem::create([
                'id' => (string) Str::uuid(),
                'library_id' => $library->id,
                'parent_id' => $parent->id,
                'type' => 'season',
                'name' => $request->input('name'),
                'index_number' => $request->input('index_number'),
            ]);
        } elseif ($type == 'episode') {
            $item = MediaItem::create([
                'id' => (string) Str::uuid(),
                'library_id' => $library->id,
                'parent_id' => $parent->id,
                'type' => 'episode',
                'name' => $request->input('name'),
                'index_number' => $request->input('index_number'),
                'parent_index_number' => $parent->index_number,
            ]);
        }

        return redirect()->back();
    }

    public function edit(MediaItem $item)
    {
        return view('admin.media.edit', compact('item'));
    }

    public function update(Request $request, MediaItem $item)
    {
        $item->mediaSources()->delete();
        foreach ($request->input('urls', []) as $urlData) {
            if ($urlData['url']) {
                $item->mediaSources()->create([
                    'url' => $urlData['url'],
                    'audio_url' => $urlData['audio_url'] ?? null,
                    'type' => $urlData['type'] ?? 'm3u8',
                ]);
            }
        }

        $item->subtitles()->delete();
        foreach ($request->input('subtitles', []) as $subData) {
            if ($subData['url']) {
                $item->subtitles()->create([
                    'url' => $subData['url'],
                    'language' => $subData['language'] ?? 'eng',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Media item updated');
    }
}
