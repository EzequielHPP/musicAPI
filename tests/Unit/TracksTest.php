<?php

namespace Tests\Unit;

use App\Models\Albums;
use App\Models\Artists;
use App\Models\Tracks;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\V1\ArtistsController;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UserTokens;
use Tests\TestCase;

class TracksTest extends TestCase
{

    /**
     * Getting the full list of Tracks
     */
    public function testGettingAllTracks()
    {

        $token = $this->_createUser();

        $headers = [
            'Accept' => '*/*',
            'Cache-Control' => 'no-cache',
            'Authorization' => $token["token"]
        ];

        $response = $this->json('GET', '/api/v1/tracks', array(), $headers);

        $this->_removeUser($token['user']);

        $response->assertStatus(200);
    }

    /**
     * Creating a valid track
     */
    public function testCreatingTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
            $this->_removeTrack($track->_hash);
        }

        $response->assertStatus(200);
    }

    /**
     * Creating a Invvalid track
     */
    public function testCreatingInvalidTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist, true);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);

        $response->assertStatus(409);
    }

    /**
     * Getting a valid track
     */
    public function testGettingTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $response = $this->json('GET', '/api/v1/tracks/'.$track->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(200);
    }

    /**
     * Getting a wrong track
     */
    public function testGettingWrongTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $response = $this->json('GET', '/api/v1/tracks/'.$track->_hash.'asdads', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(409);
    }

    /**
     * Patching a valid track
     */
    public function testPatchingTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $response = $this->json('PATCH', '/api/v1/tracks/'.$track->_hash, $newTrack, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(200);
    }

    /**
     * Patching an invalid track
     */
    public function testPatchingWrongTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $newTrack = $this->_getnewTrackArray($album, $artist, true);
        $response = $this->json('PATCH', '/api/v1/tracks/'.$track->_hash, $newTrack, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(409);
    }

    /**
     * Patching an invalid track url
     */
    public function testPatchingWrongTrackSent()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $response = $this->json('PATCH', '/api/v1/tracks/'.$track->_hash.'1212', $newTrack, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(409);
    }

    /**
     * Deleting an track
     */
    public function testDeletingTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $response = $this->json('DELETE', '/api/v1/tracks/'.$track->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(200);
    }

    /**
     * Deleting an wrong track
     */
    public function testDeletingWrongTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $response = $this->json('DELETE', '/api/v1/tracks/'.$track->_hash.'1212', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);
        $this->_removeTrack($track->_hash);

        $response->assertStatus(409);
    }

    /**
     * Deleting a deleted track
     */
    public function testDeletingDeletedTrack()
    {
        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);
        $artist = $this->_createArtist($token);
        $album = $this->_createAlbum($artist, $token);

        $newTrack = $this->_getnewTrackArray($album, $artist);
        $response = $this->json('POST', '/api/v1/tracks', $newTrack, $headers);

        $track = json_decode($response->getContent());
        if($track !== null) {
            if (is_array($track)) {
                $track = $track[0];
            }
        }

        $this->_removeTrack($track->_hash);

        $response = $this->json('DELETE', '/api/v1/tracks/'.$track->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist);
        $this->_removeAlbum($album);

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

    private function _createAlbum($artist, $token)
    {
        $newTrack = $this->_getnewAlbumArray($artist);
        $headers = $this->_getHeaders($token);
        $response = $this->json('POST', '/api/v1/albums', $newTrack, $headers);

        $album = json_decode($response->getContent());
        if (is_array($album)) {
            $album = $album[0];
        }

        return $album->_hash;
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

    private function _removeTrack($track_hash)
    {
        $track = Tracks::where('_hash', $track_hash);
        $track->forceDelete();
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

    private function _getnewTrackArray($album, $artist, $failState = false)
    {
        if ($failState) {
            return array(
                'album' => $album,
                'artists' => json_encode(array($artist)),
                'title' => md5(rand(0, 999999999)),
                'disc_number' => 1,
                'track_order' => 1
            );
        }
        return array(
            'album' => $album,
            'artists' => json_encode(array($artist)),
            'title' => md5(rand(0, 999999999)),
            'length' => 123,
            'disc_number' => 1,
            'track_order' => 1
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
