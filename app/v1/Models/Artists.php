<?php

namespace App\v1\Models;

use Illuminate\Database\Eloquent\Model;

class Artists extends Model
{
    // Laravel Model Configuration
    protected $table = 'artists'; // Table Name

    /**
     * Get the Albums associated with the artist.
     */
    public function albums()
    {
        return $this->belongsToMany(Albums::class);
    }
}
