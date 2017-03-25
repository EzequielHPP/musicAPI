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
}
