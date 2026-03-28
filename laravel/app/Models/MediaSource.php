<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaSource extends Model
{
    protected $fillable = ['media_item_id', 'url', 'type', 'audio_url'];

    public function mediaItem()
    {
        return $this->belongsTo(MediaItem::class);
    }
}
