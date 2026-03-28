<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id', 'library_id', 'parent_id', 'type', 'name', 'overview',
        'tmdb_id', 'imdb_id', 'production_year', 'poster_path',
        'backdrop_path', 'index_number', 'parent_index_number'
    ];

    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    public function mediaSources()
    {
        return $this->hasMany(MediaSource::class);
    }

    public function subtitles()
    {
        return $this->hasMany(Subtitle::class);
    }

    public function children()
    {
        return $this->hasMany(MediaItem::class, 'parent_id');
    }
}
