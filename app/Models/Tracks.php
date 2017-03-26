<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tracks extends Model
{
    // Laravel Model Configuration
    use SoftDeletes;
    protected $table = 'tracks'; // Table Name
    protected $dates = ['deleted_at'];
    protected $hidden = array('id', 'created_at','updated_at','deleted_at','pivot');

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
