<?php

namespace App\Http\Controllers\V1;

use App\Models\Artists;
use App\Models\Genres;
use App\Models\Images;
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
        $allAlbums = Albums::all();
        $return = array();
        foreach($allAlbums as $album){
            $return[] = $this->_loadAlbum($album->_hash);
        }
        return response()->json($return);
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
        // Validate request and data sent
        $validAlbum = $this->_validateAlbum($request, true);

        // Is the request valid then save and show album
        if (!is_array($validAlbum) && $validAlbum == true) {

            $album = new Albums;

            $album->name = $request->name;
            $album->_hash = md5(uniqid(rand() + time(), true));
            $album->release_date = $request->release_date;

            $album->save();

            $attachedArtists = $this->_processArtists($request->artists, $album->id);

            // Did we attach all the artists?
            // If Not then delete album and throw error
            if ($attachedArtists == false) {
                $album->forceDelete();
                return response()->json(array('status' => 'failed', 'message' => 'Invalid Artists submitted'),409);
            }

            $this->_processGenres($request->genres, $album->id);

            $this->_processImages($request->images, $album->id);

            $returnAlbum = $this->_loadAlbum($album->_hash);

            return response()->json($returnAlbum);
        }

        if (is_array($validAlbum)) {
            return response()->json($validAlbum);
        }
        return response()->json(array('status' => 'failed', 'message' => 'Invalid object submitted'),409);
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

        $album = $this->_loadAlbum($album_hash);

        return response()->json($album);
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

            //Did we get artists?
            if ($request->artists !== null) {
                $this->_processArtists($request->artists, $album->id);
            }

            // Reload album
            $album = $this->_loadAlbum($album->_hash);

            return response()->json($album);
        }

        return response()->json(array('status' => 'failed', 'message' => 'Invalid object submitted'),409);
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
        $album = Albums::where('_hash', $hash)->first();

        // Is the album valid then delete album
        if (is_object($album) && $album !== null) {
            $album->delete();

            return response()->json(array('status' => 'success', 'message' => 'Album removed'));
        }

        return response()->json(array('status' => 'failed', 'message' => 'Invalid album submitted'),409);
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




    /******************************************************
     * Private Functions
     ******************************************************/


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
        $return = $this->_validateAlbum($sentObject);

        if (!$return || is_array($return)) {
            return false;
        }

        $album = Albums::where('_hash', $hash)->first();
        if ($album == null) {
            return false;
        }

        return $album;
    }

    /**
     * Loads album and relationships
     * @param $hash
     * @return bool
     */
    private function _loadAlbum($hash)
    {

        if ($hash == null) {
            return false;
        }
        $album = Albums::where('_hash', $hash)->get();
        $album->load('artists', 'images', 'genres');

        return $album;
    }

    /**
     * Validate object sent as album
     *
     * @param $sentObject
     * @param bool $forCreation
     * @return bool
     */
    private function _validateAlbum($sentObject, $forCreation = false)
    {
        if ($sentObject->name === null) {
            return false;
        }
        if ($sentObject->release_date === null) {
            return false;
        }

        // This checks for necessary fields for the creation of the albums
        if ($forCreation) {
            if ($sentObject->genres === null) {
                return false;
            }
            if ($sentObject->images === null) {
                return false;
            }
            if ($sentObject->artists === null) {
                return false;
            }

            // Check if album already exists
            $tmpAlbum = Albums::where('name', $sentObject->name)->where('release_date', $sentObject->release_date)->first();
            if ($tmpAlbum !== null) {
                $sentArtists = $sentObject->artists;
                if (!is_array($sentArtists)) {
                    $sentArtists = json_decode($sentArtists);
                }
                $albumArtists = $tmpAlbum->artists;

                $artistsFound = 0;
                foreach ($albumArtists as $tmpArtist) {
                    if (in_array($tmpArtist->_hash, $sentArtists)) {
                        $artistsFound++;
                    }
                }

                if ($artistsFound == sizeof($albumArtists)) {
                    return array('status' => 'failed', 'message' => 'Album already exists');
                }
            }
        }

        return true;
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

            // @TODO: move this another controller (Genres Controller)
            $savedGenre = Genres::where('title', $genre)->first();
            if ($savedGenre == null) {
                $savedGenre = new Genres;
                $savedGenre->title = $genre;
                $savedGenre->_hash = md5(uniqid(rand() + time(), true));
                $savedGenre->save();
            }
            $album->genres()->syncWithoutDetaching([$savedGenre->id]);
            $attached++;
        }

        return array('attached' => $attached);
    }

    /**
     * Process images sent and attach them to required album
     *
     * @param array|string $images
     * @param integer $album_id
     * @return array
     */
    private function _processImages($images, $album_id)
    {

        if (!is_array($images)) {
            $images = json_decode($images);
        }

        if(!is_array($images)){
            $images = array($images);
        }

        $attached = 0;

        $album = Albums::find($album_id);
        $album->images()->detach();

        foreach ($images as $image) {

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

                $album->images()->syncWithoutDetaching([$savedImage->id]);
                $attached++;
            }
        }

        return array('attached' => $attached);
    }

    /**
     * Process artists sent and attach them to required album
     *
     * @param array|string $artists
     * @param integer $album_id
     * @return array
     */
    private function _processArtists($artists, $album_id)
    {

        if (!is_array($artists)) {
            $artists = json_decode($artists);
        }

        $attached = 0;

        $album = Albums::find($album_id);
        $album->artists()->detach();

        foreach ($artists as $artist) {

            // @TODO: move this another controller (Images Controller)
            // Check if image already exists
            $savedArtist = Artists::where('_hash',$artist)->first();
            if($savedArtist != null) {
                $album->artists()->syncWithoutDetaching([$savedArtist->id]);
            }

            $attached++;
        }

        return array('attached' => $attached);
    }
}
