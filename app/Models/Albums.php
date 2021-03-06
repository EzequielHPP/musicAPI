<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Albums extends Model
{
    // Laravel Model Configuration
    use SoftDeletes;
    protected $table = 'albums'; // Table Name
    protected $dates = ['deleted_at'];
    protected $hidden = array('id', 'created_at','updated_at','deleted_at','pivot');


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

    // this will delete all the child relations
    public static function boot()
    {
        parent::boot();

        Albums::deleting(function($album) {
            foreach(['tracks'] as $relation)
            {
                foreach($album->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }
}
