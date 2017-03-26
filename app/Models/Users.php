<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Models\UserTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    // Laravel Model Configuration
    use SoftDeletes;
    protected $table = 'users'; // Table Name
    protected $dates = ['deleted_at'];

    /**
     * User has one token.
     */
    public function token()
    {
        return $this->hasOne(UserTokens::class,'user_id','id');
    }
}
