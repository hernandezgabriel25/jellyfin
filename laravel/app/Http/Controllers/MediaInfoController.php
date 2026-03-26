<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaItem;

class MediaInfoController extends Controller
{
    public function getPlaybackInfo(Request $request, $itemId)
    {
        $item = MediaItem::findOrFail($itemId);

        $mediaSources = $item->mediaSources->map(fn($s) => [
            'Id' => (string) $s->id,
            'Path' => $s->url,
            'Protocol' => 'Http',
            'Type' => 'Default',
            'Container' => $s->type == 'm3u8' ? 'm3u8' : 'mpd',
            'Name' => 'Remote Source',
            'IsInfiniteStream' => true,
            'SupportsDirectStream' => true,
            'SupportsDirectPlay' => true,
            'SupportsTranscoding' => false,
            'MediaStreams' => $this->getMediaStreams($s),
            'Formats' => [$s->type == 'm3u8' ? 'm3u8' : 'mpd'],
        ]);

        return response()->json([
            'MediaSources' => $mediaSources,
            'PlaySessionId' => 'laravel-session-id',
        ]);
    }

    private function getMediaStreams($source)
    {
        $streams = [
            [
                'Type' => 'Video',
                'Index' => 0,
                'IsExternal' => false,
                'Codec' => 'h264',
            ],
            [
                'Type' => 'Audio',
                'Index' => 1,
                'IsExternal' => false,
                'Codec' => 'aac',
            ],
        ];

        // Add external audio if provided
        if ($source->audio_url) {
             $streams[] = [
                'Type' => 'Audio',
                'Index' => 2,
                'IsExternal' => true,
                'Path' => $source->audio_url,
                'Codec' => 'aac',
            ];
        }

        return $streams;
    }

    public function postPlaybackInfo(Request $request, $itemId)
    {
        return $this->getPlaybackInfo($request, $itemId);
    }
}
