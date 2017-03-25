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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request and data sent
        $validTrack = $this->_validateTrack($request);

        // Is the request valid then save and show album
        if (!is_array($validTrack) && $validTrack == true) {

            $album = Albums::where('_hash', $request->album)->first();

            $track = new Tracks;

            $track->_hash = md5(uniqid(rand() + time(), true));
            $track->album_id = $album->id;
            $track->title = $request->title;
            $track->length = $request->length;
            $track->disc_number = $request->disc_number;
            $track->track_order = $request->track_order;

            $track->save();

            $attachedArtists = $this->_processArtists($request->artists, $track->id);

            // Did we attach all the artists?
            // If Not then delete album and throw error
            if ($attachedArtists == false) {
                $track->forceDelete();
                return response()->json(array('status' => 'failed', 'message' => 'Invalid Artists submitted'));
            }

            $returnTrack = $this->_loadTrack($track->_hash);

            return response()->json($returnTrack);
        }

        if (is_array($validTrack)) {
            return response()->json($validTrack);
        }
        return response()->json(array('status' => 'failed', 'message' => 'Invalid object submitted'));
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
        if ($track_hash == null) {
            $track_hash = $artist_hash;
        }

        $track = $this->_loadTrack($track_hash);

        return response()->json($track);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $hash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $hash)
    {
        // Validate request and data sent
        $track = $this->_validateTrackAndReturnObject($request, $hash);

        // Is the request valid then save and show artist
        if (is_object($track)) {
            $track->title = $request->title;
            $track->length = $request->length;
            $track->disc_number = $request->disc_number;
            $track->track_order = $request->track_order;

            $track->save();

            // Reload track
            $track = $this->_loadTrack($track->_hash);

            return response()->json($track);
        }

        return response()->json(array('status' => 'failed', 'message' => 'Invalid object submitted'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy($hash)
    {
        //
    }


    /**
     * Validate object sent as a Track
     *
     * @param $sentObject
     * @param bool $skipArtistsAndAlbum
     * @return bool
     * @internal param bool $forCreation
     */
    private function _validateTrack($sentObject, $skipArtistsAndAlbum = false)
    {
        if (!$skipArtistsAndAlbum && $sentObject->album === null) {
            return false;
        }
        if (!$skipArtistsAndAlbum && $sentObject->artists === null) {
            return false;
        }
        if ($sentObject->title === null) {
            return false;
        }
        if ($sentObject->length === null) {
            return false;
        }
        if ($sentObject->disc_number === null) {
            return false;
        }
        if ($sentObject->track_order === null) {
            return false;
        }

        if(!$skipArtistsAndAlbum) {
            $album = Albums::where('_hash', $sentObject->album)->first();
            if ($album == null) {
                return array('status' => 'failed', 'message' => 'Invalid Album submitted');
            }

            $artistExist = $this->_processArtists($sentObject->artists);
            if ($artistExist == false) {
                return array('status' => 'failed', 'message' => 'Invalid Artists submitted');
            }

            // Check if track already exists
            $tmpTrack = Tracks::where('title', $sentObject->title)->where('album_id', $album->id)->first();
            if ($tmpTrack !== null) {
                return array('status' => 'failed', 'message' => 'Track already exists');
            }

            // Check if track already exists is position
            $tmpTrack = Tracks::where('track_order', $sentObject->track_order)->where('album_id', $album->id)->first();
            if ($tmpTrack !== null) {
                return array('status' => 'failed', 'message' => 'Other track already exists on position ' . $sentObject->track_order);
            }
        }

        return true;
    }



    /**
     * Validate sent data and check if required parameters could be sent
     * Return Artist object or false
     *
     * @param object $sentObject
     * @param string $hash
     * @return object|bool
     */
    private function _validateTrackAndReturnObject($sentObject, $hash)
    {
        $return = $this->_validateTrack($sentObject, true);

        if (!$return || is_array($return)) {
            return false;
        }

        $track = Tracks::where('_hash', $hash)->first();
        if ($track == null) {
            return false;
        }

        return $track;
    }

    /**
     * Process artist sent and attach to required album
     *
     * @param array|string $artists
     * @param null|integer $track_id
     * @return array
     * @internal param int|null $album_id
     */
    private function _processArtists($artists, $track_id = null)
    {

        if (!is_array($artists)) {
            $artists = json_decode($artists);
        }

        $attached = 0;

        if ($track_id !== null) {
            $track = Tracks::find($track_id);
            $track->artists()->detach();
        }

        foreach ($artists as $artist) {
            // Does artist exist?
            // Associate it, if not then return false
            $realArtist = Artists::where('_hash', $artist)->first();

            if ($realArtist !== null) {
                if ($track_id !== null) {
                    $track->artists()->attach($realArtist->id);
                    $attached++;
                }
            } else {
                if ($track_id !== null) {
                    $track->artists()->detach();
                }
                return false;
            }
        }
        if ($track_id === null) {
            return true;
        }
        return array('attached' => $attached);
    }

    private function _loadTrack($hash)
    {
        $trackObject = Tracks::where('_hash', $hash);
        $track = $trackObject->first();
        $track->load(['artists' => function ($query) {
            $query->with('image');
        }, 'album' => function ($query) {
            $query->with('images');
        }]);

        return $track;
    }
}
