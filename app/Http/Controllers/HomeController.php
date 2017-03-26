<?php

namespace App\Http\Controllers;

use App\Models\Users;
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
        return view('home', ['token' => $token]);
    }

    /**
     * Show the application endpoints for the api.
     *
     * @return \Illuminate\Http\Response
     */
    public function endpoints()
    {
        return view('endpoints', ['routes' => $this->getRoutes()]);
    }
}
