<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

//    Route::middleware('auth:api')->get('/user', function (Request $request) {
//        return $request->user();
//    });

    /**
     * Albums actions
     */
    Route::get('albums/{hash}/tracks', 'V1\AlbumsController@showTracks');
    Route::resource('albums', 'V1\AlbumsController', ['except' => [
        'edit', 'create'
    ]]);


    /**
     * Artists actions
     */
    Route::get('artists/{hash}/albums', 'V1\ArtistsController@showAlbums');
    Route::get('artists/{hash}/tracks', 'V1\ArtistsController@showTracks');
    Route::resource('artists', 'V1\ArtistsController', ['except' => [
        'edit', 'create'
    ]]);

    /**
     * Tracks actions
     */
    Route::resource('tracks', 'V1\TracksController', ['except' => [
        'edit', 'create'
    ]]);
});
