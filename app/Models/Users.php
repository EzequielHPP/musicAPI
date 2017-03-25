<?php

namespace App\Models;

use App\Models\UserTokens;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    // Laravel Model Configuration
    protected $table = 'users'; // Table Name

    /**
     * User has one token.
     */
    public function token()
    {
        return $this->hasOne(UserTokens::class,'user_id','id');
    }
}
