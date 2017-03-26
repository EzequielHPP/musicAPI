<?php

namespace Tests\Unit;

use App\Models\Albums;
use App\Models\Artists;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\V1\ArtistsController;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UserTokens;
use Tests\TestCase;

class AlbumsTest extends TestCase
{

    /**
     * Getting the full list of Albums
     */
    public function testGettingAllAlbums()
    {

        $token = $this->_createUser();

        $headers = [
            'Accept' => '*/*',
            'Cache-Control' => 'no-cache',
            'Authorization' => $token["token"]
        ];

        $response = $this->json('GET', '/api/v1/albums', array(), $headers);

        $this->_removeUser($token['user']);

        $response->assertStatus(200);
    }

    /**
     * Creating a valid album
     */
    public function testCreatingAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $response = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);

        $album = json_decode($response->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
            $this->_removeAlbum($album->_hash);
        }

        $response->assertStatus(200);
    }

    /**
     * Creating an invalid album
     */
    public function testCreatingInvalidAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist, true);
        $response = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);

        $response->assertStatus(409);
    }

    /**
     * Getting a album
     */
    public function testGettingAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $newAlbumResponse = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);
        $album = json_decode($newAlbumResponse->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }

        $response = $this->json('GET', '/api/v1/albums/' . $album->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album->_hash);

        $response->assertStatus(200);
    }

    /**
     * Getting a wrong album
     */
    public function testGettingWrongAlbum()
    {

        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);

        $response = $this->json('GET', '/api/v1/albums/' . rand(1, 9999999999), array(), $headers);

        $this->_removeUser($token['user']);

        $response->assertStatus(409);
    }

    /**
     * Updating an album
     */
    public function testUpdatingAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $response = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);

        $album = json_decode($response->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }

        $newAlbum = $this->_getnewAlbumArray($artist);
        $response = $this->json('PATCH', '/api/v1/albums/'.$album->_hash, $newAlbum, $headers);

        $this->_removeAlbum($album->_hash);
        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);


        $response->assertStatus(200);
    }

    /**
     * Updating wrongly an album
     */
    public function testUpdatingWrongFieldsAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $response = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);

        $album = json_decode($response->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }

        $newAlbum = $this->_getnewAlbumArray($artist, true);
        $response = $this->json('PATCH', '/api/v1/albums/'.$album->_hash, $newAlbum, $headers);

        $this->_removeAlbum($album->_hash);
        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);


        $response->assertStatus(409);
    }

    /**
     * Getting an album tracks
     */
    public function testGettingAlbumTracks()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $newAlbumResponse = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);
        $album = json_decode($newAlbumResponse->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }

        $response = $this->json('GET', '/api/v1/albums/' . $album->_hash.'/tracks', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album->_hash);

        $response->assertStatus(200);
    }

    /**
     * Getting a wrong album tracks
     */
    public function testGettingWrongAlbumTracks()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $newAlbumResponse = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);
        $album = json_decode($newAlbumResponse->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }

        $response = $this->json('GET', '/api/v1/albums/' . $album->_hash.'1212/tracks', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album->_hash);

        $response->assertStatus(409);
    }

    /**
     * Deleting Album
     */
    public function testDeletingAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $newAlbumResponse = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);
        $album = json_decode($newAlbumResponse->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }

        $response = $this->json('DELETE', '/api/v1/albums/' . $album->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album->_hash);

        $response->assertStatus(200);
    }

    /**
     * Deleting Wrong Album
     */
    public function testDeletingWrongAlbum()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);

        $newAlbum = $this->_getnewAlbumArray($artist);
        $newAlbumResponse = $this->json('POST', '/api/v1/albums', $newAlbum, $headers);
        $album = json_decode($newAlbumResponse->getContent());
        if($album !== null) {
            if (is_array($album)) {
                $album = $album[0];
            }
        }
        $this->_removeAlbum($album->_hash);

        $response = $this->json('DELETE', '/api/v1/albums/' . $album->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);

        $response->assertStatus(409);
    }




    private function _createUser()
    {
        $user = factory(Users::class)->create();

        $controller = new Controller();
        $this->token = $controller->gen_uuid();

        UserTokens::create([
            'user_id' => $user->id,
            'token' => $this->token,
        ]);

        return array('token' => $this->token, 'user' => $user->id);
    }

    private function _createArtist($token)
    {
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $response = $this->json('POST', '/api/v1/artists', $newArtist, $headers);

        $artist = json_decode($response->getContent());
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        return $artist->_hash;
    }

    private function _removeUser($userId)
    {

        $user = Users::find($userId);
        $user->forceDelete();

        $userToken = UserTokens::where('user_id', $userId)->first();
        $userToken->forceDelete();
    }

    private function _removeArtist($artist_hash)
    {
        $artist = Artists::where('_hash', $artist_hash);
        $artist->forceDelete();
    }

    private function _removeAlbum($album_hash)
    {
        $album = Albums::where('_hash', $album_hash);
        $album->forceDelete();
    }

    private function _getNewArtistArray($failState = false)
    {
        if ($failState) {
            return array(
                'image' => json_encode(array(
                    "name" => "Jomsviking",
                    "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                    "width" => 300,
                    "height" => 300
                ))
            );
        }
        return array(
            'name' => md5(rand(0, 999999999)),
            'image' => json_encode(array(
                "name" => "Jomsviking",
                "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                "width" => 300,
                "height" => 300
            ))
        );
    }

    private function _getHeaders($token)
    {
        return array(
            'Accept' => '*/*',
            'Cache-Control' => 'no-cache',
            'Authorization' => $token["token"]
        );
    }

    private function _getnewAlbumArray($artistHash, $invalidArray = false)
    {
        if($invalidArray){
            return array(
                "release_date" => date("Y-m-d"),
                "genres" => json_encode(array("Pop")),
                "images" => json_encode(array(
                    "name" => "Jomsviking",
                    "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                    "width" => 300,
                    "height" => 300
                )),
                "artists"=> json_encode(array($artistHash))
            );
        }
        return array(
            "name" => md5(rand(1, 9999999999999)),
            "release_date" => date("Y-m-d"),
            "genres" => json_encode(array("Pop")),
            "images" => json_encode(array(
                "name" => "Jomsviking",
                "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                "width" => 300,
                "height" => 300
            )),
            "artists"=> json_encode(array($artistHash))
        );
    }
}
