<?php

namespace App\Http\Controllers\V1;

use App\Models\Genres;
use App\Models\Images;
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
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
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
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
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
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
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
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param  string $hash
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorizationHeader $request, $hash)
    {
        // Validate request and data sent
        $album = $this->_validateAlbumAndReturnObject($request, $hash);

        // Is the request valid then save and show album
        if (is_object($album)) {
            $album->name = $request->name;
            $album->release_date = $request->release_date;

            $album->save();

            //Did we get Genres?
            if ($request->genres !== null) {
                $this->_processGenres($request->genres, $album->id);
            }

            //Did we get images?
            if ($request->images !== null) {
                $this->_processImages($request->images, $album->id);
            }

            // Load extra fields
            $album->load('artists', 'images', 'genres');

            return response()->json($album);
        }

        return response()->json(array('status' => 'failed', 'message' => 'Invalid object submitted'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Controllers\V1\Requests\AuthorizationHeader $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorizationHeader $request, $id)
    {
        //
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
        $albumObject = Albums::where('_hash', $hash);
        $album = $albumObject->first();
        $album->load('images');
        $album->tracks;

        return response()->json($album);
    }

    /**
     * Validate sent data and check if required parameters could be sent
     * Return Album object or false
     *
     * @param object $sentObject
     * @param string $hash
     * @return object|bool
     */
    private function _validateAlbumAndReturnObject($sentObject, $hash)
    {
        if ($sentObject->name === null) {
            return false;
        }
        if ($sentObject->release_date === null) {
            return false;
        }

        $album = Albums::where('_hash', $hash)->first();
        if ($album == null) {
            return false;
        }

        return $album;
    }

    /**
     * Process genres sent and attach them to required album
     *
     * @param array|string $genres
     * @param integer $album_id
     * @return array
     */
    private function _processGenres($genres, $album_id)
    {

        if (!is_array($genres)) {
            $genres = json_decode($genres);
        }

        $attached = 0;

        $album = Albums::find($album_id);
        $album->genres()->detach();

        foreach ($genres as $genre) {
            // Does genre already exist?
            // If not then generate it and associate
            // else associate

            $savedGenre = Genres::where('title', $genre)->first();
            if ($savedGenre == null) {
                $savedGenre = new Genres;
                $savedGenre->title = $genre;
                $savedGenre->_hash = md5(uniqid(rand()+time(), true));
                $savedGenre->save();
            }

            $album->genres()->attach($savedGenre->id);
            $attached++;
        }

        return array('attached' => $attached);
    }

    /**
     * Process images sent and attach them to required album
     *
     * @param array|string $genres
     * @param integer $album_id
     * @return array
     */
    private function _processImages($images, $album_id)
    {

        if (!is_array($images)) {
            $images = json_decode($images);
        }

        $attached = 0;

        $album = Albums::find($album_id);
        $album->images()->detach();

        foreach ($images as $image) {

            if (property_exists($image, 'name') && property_exists($image, 'file') && property_exists($image, 'width') && property_exists($image, 'height')) {
                $savedImage = new Images;
                $savedImage->name = $image->name;
                $savedImage->file = $image->file;
                $savedImage->width = $image->width;
                $savedImage->height = $image->height;
                $savedImage->save();


                $album->images()->attach($savedImage->id);
                $attached++;
            }
        }

        return array('attached' => $attached);
    }
}
