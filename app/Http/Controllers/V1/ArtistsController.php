<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Artists;
use App\Models\Albums;
use App\Models\Tracks;
use App\Http\Controllers\V1\Requests\AuthorizationHeader;

class ArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AuthorizationHeader $request)
    {
        $artistsObject = Artists::all();


        return response()->json($artistsObject);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorizationHeader $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param  string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AuthorizationHeader $request, $hash)
    {

        $artistObject = Artists::where('_hash', $hash);
        $artist = $artistObject->first()->load('image');

        return response()->json($artist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorizationHeader $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorizationHeader $request, $hash)
    {
        //
    }

    /**
     * Show all albums.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAlbums(AuthorizationHeader $request, $hash)
    {
        $artistObject = Artists::where('_hash', $hash);
        $artist = $artistObject->first();
        $artist->load('image');
        $artist->albums->load('images', 'genres');

        return response()->json($artist);
    }

    /**
     * Show all tracks.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTracks(AuthorizationHeader $request, $hash)
    {
        $artistObject = Artists::where('_hash', $hash);
        $artist = $artistObject->first();
        $artist->load('image');
        $artist->tracks;

        return response()->json($artist);
    }
}
