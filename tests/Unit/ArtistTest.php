<?php

namespace Tests\Unit;

use App\Models\Artists;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\V1\ArtistsController;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UserTokens;
use Tests\TestCase;

class ArtistTest extends TestCase
{
    use WithoutMiddleware;

    private $token = '';

    /**
     * Getting the full list of users
     */
    public function testGettingAllArtist()
    {

        $token = $this->_createUser();

        $headers = [
            'Accept' => '*/*',
            'Cache-Control' => 'no-cache',
            'Authorization' => $token["token"]
        ];

        $response = $this->json('GET', '/api/v1/artists', array(), $headers);

        $this->_removeUser($token['user']);

        $response->assertStatus(200);
    }

    /**
     * Creating a valid artist
     */
    public function testCreatingArtist()
    {

        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $response = $this->json('POST', '/api/v1/artists', $newArtist, $headers);

        $this->_removeUser($token['user']);

        $artist = json_decode($response->getContent());
        if (is_array($artist)) {
            $artist = $artist[0];
        }
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(200);
    }

    /**
     * Creating an artist, but with missing fields
     */
    public function testCreatingArtistFailure()
    {

        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray(true);
        $headers = $this->_getHeaders($token);

        $response = $this->json('POST', '/api/v1/artists', $newArtist, $headers);

        $this->_removeUser($token['user']);

        $response->assertStatus(409);
    }

    /**
     * Getting a specific artist
     */
    public function testGettingArtist()
    {

        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('GET', '/api/v1/artists/' . $artist->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(200);
    }

    /**
     * Getting a wrong artist
     */
    public function testGettingWrongArtist()
    {

        $token = $this->_createUser();
        $headers = $this->_getHeaders($token);

        $response = $this->json('GET', '/api/v1/artists/' . rand(1, 9999999999), array(), $headers);

        $this->_removeUser($token['user']);

        $response->assertStatus(409);
    }

    /**
     * Patching a specific artist
     */
    public function testPatchingArtist()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $newArtist = array(
            'name' => md5(rand(1, 300)),
            'image' => json_encode(array(
                "name" => "Jomsviking",
                "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                "width" => 300,
                "height" => 300
            ))
        );

        $response = $this->json('PATCH', '/api/v1/artists/' . $artist->_hash, $newArtist, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(200);
    }

    /**
     * Patching a wrong artist
     */
    public function testPatchingWrongArtist()
    {

        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }
        $newArtist = $this->_getNewArtistArray(true);

        $response = $this->json('PATCH', '/api/v1/artists/' . $artist->_hash . rand(1, 9), $newArtist, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(409);
    }

    /**
     * Patching an artist with wrong data
     */
    public function testPatchingArtistWithWrongData()
    {

        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $newArtist = array(
            'name' => md5(rand(1, 300)),
            'images' => json_encode(array(
                "name" => "Jomsviking",
                "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                "width" => 300,
                "height" => 300
            ))
        );

        $response = $this->json('PATCH', '/api/v1/artists/' . $artist->_hash . rand(1, 9), $newArtist, $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(409);
    }

    /**
     * Getting a specific artists albums
     */
    public function testGettingArtistAlbums()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('GET', '/api/v1/artists/' . $artist->_hash . '/albums', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(200);
    }

    /**
     * Getting a specific Wrong Artists albums
     */
    public function testGettingWrongArtistAlbums()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('GET', '/api/v1/artists/' . $artist->_hash . '1212/albums', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(409);
    }

    /**
     * Getting a specific artists albums
     */
    public function testGettingArtistTracks()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('GET', '/api/v1/artists/' . $artist->_hash . '/tracks', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(200);
    }

    /**
     * Getting a specific Wrong Artists albums
     */
    public function testGettingWrongArtistTracks()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('GET', '/api/v1/artists/' . $artist->_hash . '1212/tracks', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(409);
    }

    /**
     * Deleting Artist
     */
    public function testDeletingArtist()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('DELETE', '/api/v1/artists/' . $artist->_hash, array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

        $response->assertStatus(200);
    }

    /**
     * Deleting wrong Artist
     */
    public function testDeletingWrongArtist()
    {
        $token = $this->_createUser();
        $newArtist = $this->_getNewArtistArray();
        $headers = $this->_getHeaders($token);

        $responseCreate = $this->json('POST', '/api/v1/artists', $newArtist, $headers)->getContent();
        $artist = json_decode($responseCreate);
        if (is_array($artist)) {
            $artist = $artist[0];
        }

        $response = $this->json('DELETE', '/api/v1/artists/' . $artist->_hash . '121212', array(), $headers);

        $this->_removeUser($token['user']);
        $this->_removeArtist($artist->_hash);

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

    private function _getNewArtistArray($failState = false)
    {
        if($failState){
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
            'name' => md5(rand(0,999999999)),
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
}
