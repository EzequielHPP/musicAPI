<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Albums;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        echo 'hello';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function show($artist_hash, $album_hash = null)
    {
        if ($album_hash == null) {
            $album_hash = $artist_hash;
        }
        $album = Albums::where('_hash', $album_hash)->get()->load('images', 'genres');

        return json_encode($album);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $artist_hash
     * @param null $album_hash
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function tracks($artist_hash, $album_hash = null)
    {
        if ($album_hash == null) {
            $album_hash = $artist_hash;
        }
        $album = Albums::where('_hash', $album_hash)->first();

        return response()->json($album->load('tracks'));
    }
}