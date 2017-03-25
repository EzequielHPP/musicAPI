<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracks extends Model
{
    // Laravel Model Configuration
    protected $table = 'tracks'; // Table Name

    /**
     * Get the Album associated with the track.
     */
    public function album()
    {
        return $this->belongsTo(Albums::class);
    }

    /**
     * Tracks belongs to Artists.
     */
    public function artists()
    {
        return $this->belongsToMany(Artists::class);
    }
}
