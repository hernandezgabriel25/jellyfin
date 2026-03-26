<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $fillable = ['name', 'type'];

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class);
    }
}
