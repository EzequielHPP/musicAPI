<?php

namespace App\Http\Controllers\V1;

use App\Models\Images;
use App\Http\Controllers\Controller;
use App\Models\Artists;
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
        $allArtists = Artists::all();
        $return = array();
        foreach($allArtists as $artist){
            $return[] = $this->_loadArtist($artist->_hash);
        }
        return response()->json($return);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorizationHeader $request)
    {
        // Validate request and data sent
        $validArtist = $this->_validateArtist($request, true);

        // Is the request valid then save and show artist
        if (!is_array($validArtist) && $validArtist == true) {

            $imageId = $this->_processImage($request->image);

            if(!is_array($imageId)) {
                $artist = new Artists;

                $artist->name = $request->name;
                $artist->image_id = $imageId;
                $artist->_hash = md5(uniqid(rand() + time(), true));

                $artist->save();

                $returnArtist = $this->_loadArtist($artist->_hash);

                return response()->json($returnArtist);
            }

            $validArtist = $imageId;
        }

        if (is_array($validArtist)) {
            return response()->json($validArtist);
        }
        return response('',409)->json(array('status' => 'failed', 'message' => 'Invalid object submitted'));
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
     * @param  int $hash
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorizationHeader $request, $hash)
    {
        // Validate request and data sent
        $artist = $this->_validateArtistAndReturnObject($request, $hash);

        // Is the request valid then save and show artist
        if (is_object($artist)) {
            $artist->name = $request->name;

            $artist->save();

            //Did we get images?
            if ($request->image !== null) {
                $this->_processImage($request->image, $artist->id);
            }

            // Reload artist
            $artist = $this->_loadArtist($artist->_hash);

            return response()->json($artist);
        }

        return response('',409)->json(array('status' => 'failed', 'message' => 'Invalid object submitted'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param  string $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorizationHeader $request, $hash)
    {
        $artist = Artists::where('_hash', $hash)->first();

        // Is the artist valid then delete artist
        if (is_object($artist) && $artist !== null) {
            $artist->delete();

            return response()->json(array('status' => 'success', 'message' => 'Artist removed'));
        }

        return response('',409)->json(array('status' => 'failed', 'message' => 'Invalid artist submitted'));
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





    /******************************************************
     * Private Functions
     ******************************************************/

    /**
     * Validate sent data and check if required parameters could be sent
     * Return Artist object or false
     *
     * @param object $sentObject
     * @param string $hash
     * @return object|bool
     */
    private function _validateArtistAndReturnObject($sentObject, $hash)
    {
        $return = $this->_validateArtist($sentObject);

        if (!$return || is_array($return)) {
            return false;
        }

        $artist = Artists::where('_hash', $hash)->first();
        if ($artist == null) {
            return false;
        }

        return $artist;
    }

    /**
     * Validate object sent as artist
     *
     * @param $sentObject
     * @param bool $forCreation
     * @return bool
     */

    private function _validateArtist($sentObject, $forCreation = false)
    {
        if ($sentObject->name === null) {
            return false;
        }
        if ($sentObject->image === null) {
            return false;
        }

        // This checks for necessary fields for the creation of the albums
        if ($forCreation) {

            // Check if album already exists
            $tmpArtist = Artists::where('name', $sentObject->name)->first();
            if ($tmpArtist !== null) {
                return array('status' => 'failed', 'message' => 'Artist already exists');
            }
        }

        return true;
    }

    /**
     * Load Artist
     * @param $hash
     * @return bool
     */
    private function _loadArtist($hash)
    {

        if ($hash == null) {
            return false;
        }
        $album = Artists::where('_hash', $hash)->get();
        $album->load('image');

        return $album;
    }

    /**
     * Process the image that was sent and attach to required artist
     *
     * @param array|string $image
     * @param integer $artist_id
     * @return array
     */
    private function _processImage($image, $artist_id = null)
    {
        if (!is_object($image)) {
            $image = json_decode($image);
        }


        if (property_exists($image, 'name') && property_exists($image, 'file') && property_exists($image, 'width') && property_exists($image, 'height')) {


            // @TODO: move this another controller (Images Controller)
            // Check if image already exists
            $savedImage = Images::where('file',$image->file)->first();
            if($savedImage == null) {
                $savedImage = new Images;
                $savedImage->name = $image->name;
                $savedImage->file = $image->file;
                $savedImage->width = $image->width;
                $savedImage->height = $image->height;
                $savedImage->save();
            }

            if ($artist_id != null) {
                $artist = Artists::find($artist_id);
                $artist->image_id = $savedImage->id;
                $artist->save();
            }

            return $savedImage->id;
        }

        return array('status' => 'failed', 'message' => 'Invalid image submitted');
    }
}
