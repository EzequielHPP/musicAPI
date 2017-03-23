<?php

namespace App\v1\Models;

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
}
