<?php

namespace Tests\Unit;

use App\Http\Controllers\V1\ArtistsController;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UserTokens;
use Tests\TestCase;

class ArtistTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    private $token = '';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreatingUserTest()
    {
        $user = factory(Users::class)->create();

        $controller = new Controller();
        $this->token = $controller->gen_uuid();

        UserTokens::create([
            'user_id' => $user->id,
            'token' => $this->token,
        ]);
    }


    public function testCreatingArtist()
    {

        $newArtist = array(
            'name' => 'Random Artist Name',
            'image' => json_encode(array(
                "name" => "Jomsviking",
                "file" => "https://upload.wikimedia.org/wikipedia/en/7/7a/AmonAmarthJomsviking.jpg",
                "width" => 300,
                "height" => 300
            ))
        );

        $response = $this->json('POST', action('V1\AlbumsController@store'), $newArtist, ['HTTP_Authorization' => 'Authorization', $this->token]);

        $response->assertStatus(200);
    }
}
