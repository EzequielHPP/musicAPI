<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Artists;
use App\Models\Albums;
use App\Models\Tracks;

class ArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $artistsObject = Artists::all();


        return response()->json($artistsObject);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($hash)
    {

        $artistObject = Artists::where('_hash', $hash);
        $artist = $artistObject->first()->load('image');

        return response()->json($artist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($hash)
    {
        //
    }

    /**
     * Show all albums.
     *
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAlbums($hash)
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
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTracks($hash)
    {
        $artistObject = Artists::where('_hash', $hash);
        $artist = $artistObject->first();
        $artist->load('image');
        $artist->tracks;

        return response()->json($artist);
    }
}
