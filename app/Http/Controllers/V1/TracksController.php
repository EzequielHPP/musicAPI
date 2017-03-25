<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Artists;
use App\Models\Albums;
use App\Models\Tracks;

class TracksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return response()->json(Tracks::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $artist_hash
     * @param string $album_hash
     * @param string $track_hash
     * @return \Illuminate\Http\Response
     */
    public function show($artist_hash, $album_hash = null, $track_hash = null)
    {
        if($track_hash == null){
            $track_hash = $artist_hash;
        }

        $trackObject = Tracks::where('_hash', $track_hash);
        $track = $trackObject->first();
        $track->load(['artists' => function ($query) {
            $query->with('image');
        }, 'album' => function ($query) {
            $query->with('images');
        }]);

        return response()->json($track);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $hash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $hash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy($hash)
    {
        //
    }
}
