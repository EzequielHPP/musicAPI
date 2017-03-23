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
    public function image()
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
        return $this->hasMany(Tracks::class,'id','id');
    }
}