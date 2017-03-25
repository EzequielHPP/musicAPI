<?php

namespace App\Http\Controllers;

use App\Models\UserTokens;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Users::find(Auth::user()->id);
        $token = $user->token->token;
        if($token === null){
            $token = $this->gen_uuid();
            UserTokens::create([
                'user_id' => Auth::user()->id,
                'token' => $token,
            ]);
        }
        return view('home', ['token' => $token]);
    }
}
