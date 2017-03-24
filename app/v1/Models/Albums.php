<?php

namespace App\v1\Models;

use Illuminate\Database\Eloquent\Model;

class Albums extends Model
{
    // Laravel Model Configuration
    protected $table = 'albums'; // Table Name

    /**
     * Get the image associated with the albums.
     */
    public function images()
    {
        return $this->hasMany(Images::class);
    }

    /**
     * Album belongs to Artists.
     */
    public function artists()
    {
        return $this->belongsToMany(Artists::class);
    }

    /**
     * Album has many tracks.
     */
    public function tracks()
    {
        return $this->hasMany(Tracks::class,'album_id','id');
    }

    /**
     * Album has many genres.
     */
    public function genres()
    {
        return $this->belongsToMany(Genres::class);
    }
}
