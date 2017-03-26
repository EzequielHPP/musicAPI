<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    // Laravel Model Configuration
    use SoftDeletes;
    protected $table = 'images'; // Table Name
    protected $dates = ['deleted_at'];
    protected $hidden = array('created_at','updated_at','deleted_at','pivot');
}
