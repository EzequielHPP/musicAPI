<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artists extends Model
{
    // Laravel Model Configuration
    use SoftDeletes;
    protected $table = 'artists'; // Table Name
    protected $dates = ['deleted_at'];
    protected $hidden = array('id', 'image_id','created_at','updated_at','deleted_at','pivot');

    /**
     * Get the Albums associated with the artist.
     */
    public function albums()
    {
        return $this->belongsToMany(Albums::class);
    }

    /**
     * Get the Tracks associated with the artist.
     */
    public function tracks()
    {
        return $this->belongsToMany(Tracks::class);
    }

    public function image()
    {
        return $this->hasOne(Images::class,'id','image_id');
    }

    // this will delete all the child relations
    public static function boot()
    {
        parent::boot();

        Artists::deleting(function($artist) {
            foreach(['tracks', 'albums'] as $relation)
            {
                foreach($artist->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }
}
