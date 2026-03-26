<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library;
use App\Models\MediaItem;

class ItemController extends Controller
{
    public function getViews(Request $request)
    {
        $libraries = Library::all();
        $items = $libraries->map(function ($lib) {
            return [
                'Name' => $lib->name,
                'Id' => (string) $lib->id,
                'Type' => 'CollectionFolder',
                'CollectionType' => $lib->type,
                'ImageTags' => [],
            ];
        });

        return response()->json([
            'Items' => $items,
            'TotalRecordCount' => $items->count(),
            'StartIndex' => 0,
        ]);
    }

    public function getItems(Request $request)
    {
        $parentId = $request->query('ParentId');
        $includeItemTypes = $request->query('IncludeItemTypes');

        $query = MediaItem::query();

        if ($parentId) {
            // Check if ParentId is a Library ID or a MediaItem ID
            if (is_numeric($parentId)) {
                $query->where('library_id', $parentId)->whereNull('parent_id');
            } else {
                $query->where('parent_id', $parentId);
            }
        }

        if ($includeItemTypes) {
            $types = explode(',', $includeItemTypes);
            $query->whereIn('type', $types);
        }

        $items = $query->get()->map(function ($item) {
            return $this->getItemDto($item);
        });

        return response()->json([
            'Items' => $items,
            'TotalRecordCount' => $items->count(),
            'StartIndex' => 0,
        ]);
    }

    public function getItemById($itemId)
    {
        $item = MediaItem::findOrFail($itemId);
        return response()->json($this->getItemDto($item));
    }

    private function getItemDto($item)
    {
        $dto = [
            'Name' => $item->name,
            'Id' => $item->id,
            'Type' => $this->mapType($item->type),
            'Overview' => $item->overview,
            'ProductionYear' => (int) $item->production_year,
            'IndexNumber' => $item->index_number,
            'ParentIndexNumber' => $item->parent_index_number,
            'ImageTags' => [],
            'UserData' => [
                'PlaybackPositionTicks' => 0,
                'PlayCount' => 0,
                'IsFavorite' => false,
                'Played' => false,
            ],
            'ServerId' => 'laravel-server',
            'RunTimeTicks' => 36000000000, // Dummy 1 hour
        ];

        if ($item->poster_path) {
            $dto['ImageTags']['Primary'] = 'primary_tag';
        }
        if ($item->backdrop_path) {
            $dto['ImageTags']['Backdrop'] = 'backdrop_tag';
        }

        if ($item->type == 'movie' || $item->type == 'episode') {
            $dto['MediaSources'] = $item->mediaSources->map(fn($s) => [
                'Id' => (string) $s->id,
                'Path' => $s->url,
                'Protocol' => 'Http',
                'SupportsDirectStream' => true,
                'SupportsDirectPlay' => true,
                'IsInfiniteStream' => true,
            ]);
        }

        if ($item->parent_id) {
            $dto['ParentId'] = $item->parent_id;
        }

        return $dto;
    }

    private function mapType($type)
    {
        return match($type) {
            'movie' => 'Movie',
            'series' => 'Series',
            'season' => 'Season',
            'episode' => 'Episode',
            default => 'Movie'
        };
    }
}
