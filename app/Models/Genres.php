<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genres extends Model
{
    // Laravel Model Configuration
    use SoftDeletes;
    protected $table = 'genres'; // Table Name
    protected $dates = ['deleted_at'];



    /**
     * Album has many genres.
     */
    public function albums()
    {
        return $this->belongsToMany(Albums::class);
    }

    // this will delete all the child relations
    public static function boot()
    {
        parent::boot();

        Genres::deleting(function($genre) {
            foreach(['albums'] as $relation)
            {
                foreach($genre->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }
}
