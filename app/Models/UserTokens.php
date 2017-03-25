<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTokens extends Model
{
    // Laravel Model Configuration
    protected $table = 'users_tokens'; // Table Name
    protected $fillable = ['user_id', 'token'];

    /**
     * Token belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(Users::class,'id','user_id');
    }

}
