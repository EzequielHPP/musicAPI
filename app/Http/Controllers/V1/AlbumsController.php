<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Albums;
use App\Http\Controllers\V1\Requests\CreateAlbum;
use App\Http\Controllers\V1\Requests\AuthorizationHeader;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AuthorizationHeader $request)
    {
        $albums = Albums::all();

        return json_encode($albums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorizationHeader $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $hash
     * @return \Illuminate\Http\Response
     */
    public function show(AuthorizationHeader $request, $artist_hash, $album_hash = null)
    {
        if ($album_hash == null) {
            $album_hash = $artist_hash;
        }
        $album = Albums::where('_hash', $album_hash)->get();
        $album->load('artists', 'images', 'genres');

        return json_encode($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $hash
     * @return \Illuminate\Http\Response
     */
    public function update(CreateAlbum $request, $hash)
    {
        var_dump('asdasd');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show all tracks.
     *
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTracks($hash)
    {
        $albumObject = Albums::where('_hash', $hash);
        $album = $albumObject->first();
        $album->load('images');
        $album->tracks;

        return response()->json($album);
    }
}
