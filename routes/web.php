<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'api/v1'], function () {

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    /**
     * Albums actions
     */
    Route::resource('albums', 'Api\V1\AlbumsController');


    /**
     * Artists actions
     */
    Route::resource('artists', 'Api\V1\ArtistsController');
    Route::get('artists/{hash}/albums', 'Api\V1\ArtistsController@showAlbums');
    Route::get('artists/{artist_hash}/albums/{album_hash}', 'Api\V1\AlbumsController@show');
    Route::get('artists/{artist_hash}/albums/{album_hash}/tracks', 'Api\V1\AlbumsController@tracks');
    Route::get('artists/{artist_hash}/albums/{album_hash}/tracks/{track_hash}', 'Api\V1\TracksController@show');

    /**
     * Tracks actions
     */
    Route::resource('tracks', 'Api\V1\TracksController');
});