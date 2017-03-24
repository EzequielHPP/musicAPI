<?php

namespace App\Models;

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
        return $this->belongsToMany(Images::class);
    }

    /**
     * Album belongs to Artists.
     */
    public function artists()
    {
        return $this->hasMany(Artists::class);
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
