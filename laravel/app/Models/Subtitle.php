<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    protected $fillable = ['media_item_id', 'url', 'language', 'format'];

    public function mediaItem()
    {
        return $this->belongsTo(MediaItem::class);
    }
}
