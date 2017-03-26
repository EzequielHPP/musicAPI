<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getRoutes()
    {
        $routes = array();
        /*
         * Albums
         */
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/albums",
            "description" => "Get all albums",
            "returns" => "Array of Albums objects",
            "urlelements" => array(),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '[[{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth"}],"images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}],"genres":[{"_hash":"8bbd9836f4b7b03c654a06c2c584e5c8","title":"Melodic Death Metal"}]}],...]',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "POST",
            "url" => "/api/v1/albums",
            "description" => "Create a new Album",
            "returns" => "Album object",
            "urlelements" => array(),
            "bodyData" => array(
                array('name', 'string', 'Name of the new album', 'New Album Name'),
                array('release_date', 'string', 'Release date', date('Y-m-d')),
                array('genres', 'array', 'Array of strings of the genres', '["Pop","Rock"]'),
                array('images', 'array', 'Array of image(s) objects', '[{"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300},...]'),
                array('artists', 'array', 'Array of artist(s) hashes', '["e79c8fee899e31ca99bbb50afb7df6ab",...]'),
            ),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '[{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25"}]',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/albums/{album}",
            "description" => "Get a Album",
            "returns" => "Album object, with Artist info and Genres",
            "urlelements" => array(
                array('{album}', 'Hash of the album in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '[{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth"}],"images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}],"genres":[{"_hash":"8bbd9836f4b7b03c654a06c2c584e5c8","title":"Melodic Death Metal"}]}]',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "PATCH",
            "url" => "/api/v1/albums/{album}",
            "description" => "Update an Album",
            "returns" => "Album object, with Artist info and Genres",
            "urlelements" => array(
                array('{album}', 'Hash of the album in question')
            ),
            "bodyData" => array(
                array('name', 'string', 'Name of the new album', 'New Album Name'),
                array('release_date', 'string', 'Release date', date('Y-m-d')),
                array('genres', 'array', 'Array of strings of the genres', '["Pop","Rock"]'),
                array('images', 'array', 'Array of image(s) objects', '[{"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300},...]'),
                array('artists', 'array', 'Array of artist(s) hashes', '["e79c8fee899e31ca99bbb50afb7df6ab",...]'),
            ),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '[{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth"}],"images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}],"genres":[{"_hash":"8bbd9836f4b7b03c654a06c2c584e5c8","title":"Melodic Death Metal"}]}]',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "DELETE",
            "url" => "/api/v1/albums/{album}",
            "description" => "Delete an Album",
            "returns" => "Confirmation array",
            "urlelements" => array(
                array('{album}', 'Hash of the album in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"status":"success","message":"Album removed"}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/albums/{album}/tracks",
            "description" => "Get all tracks for a certain album",
            "returns" => "Album object, with array of Track objects",
            "urlelements" => array(
                array('{album}', 'Hash of the album in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}],"tracks":[{"_hash":"f1ca96adf6ebeec5e6f206f6525478a8","album_id":1,"title":"First Kill","length":261,"disc_number":1,"track_order":1},...]}',
            "exampleBadReply" => ''
        );


        /*
         * Artists
         */
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/artists",
            "description" => "Get all Artists",
            "returns" => "Array of Artist objects",
            "urlelements" => array(),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '[[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}],[{"_hash":"cd1cdfb68b4ab83a934dbef82285d486","name":"To Be Deleted","image":{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}}],...]',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "POST",
            "url" => "/api/v1/artists",
            "description" => "Create a new Artist",
            "returns" => "Artist object",
            "urlelements" => array(),
            "bodyData" => array(
                array('name', 'string', 'Name of the new Artist', 'New Artist Name'),
                array('image', 'array', 'Image object', '{"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}')
            ),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/artists/{artist}",
            "description" => "Get a Artist",
            "returns" => "Artist object",
            "urlelements" => array(
                array('{artist}', 'Hash of the artist in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "PATCH",
            "url" => "/api/v1/artists/{artist}",
            "description" => "Update Artist",
            "returns" => "Artist object",
            "urlelements" => array(
                array('{artist}', 'Hash of the artist in question')
            ),
            "bodyData" => array(
                array('name', 'string', 'Name of the Artist', 'New Artist Name'),
                array('image', 'array', 'Image object', '{"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}')
            ),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "DELETE",
            "url" => "/api/v1/artists/{artist}",
            "description" => "Delete an Artist",
            "returns" => "Confirmation array",
            "urlelements" => array(
                array('{artist}', 'Hash of the artist in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"status":"success","message":"Artist removed"}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/artists/{artist}/albums",
            "description" => "Get all albums by an Artist",
            "returns" => "Artist object with array of albums",
            "urlelements" => array(
                array('{artist}', 'Hash of the artist in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413},"albums":[{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}],"genres":[{"_hash":"8bbd9836f4b7b03c654a06c2c584e5c8","title":"Melodic Death Metal"},...]}]}',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/artists/{artist}/tracks",
            "description" => "Get all tracks by an Artist",
            "returns" => "Artist object with array of tracks",
            "urlelements" => array(
                array('{artist}', 'Hash of the artist in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413},"tracks":[{"_hash":"f1ca96adf6ebeec5e6f206f6525478a8","album_id":1,"title":"First Kill","length":261,"disc_number":1,"track_order":1},...]}',
            "exampleBadReply" => ''
        );


        /*
         * Tracks
         */
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/tracks",
            "description" => "Get all tracks",
            "returns" => "Array of Tracks objects",
            "urlelements" => array(),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '[{"_hash":"f1ca96adf6ebeec5e6f206f6525478a8","album_id":1,"title":"First Kill","length":261,"disc_number":1,"track_order":1,"artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}],"album":{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}]}},...]',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "POST",
            "url" => "/api/v1/tracks",
            "description" => "Create a new Track",
            "returns" => "Track object",
            "urlelements" => array(),
            "bodyData" => array(
                array('album', 'string', 'Hash of the album of the track', 'e0fd4120d2126ee56a356d03a555bf97'),
                array('artists', 'array', 'Array of Artist hashes', '["7596da80a32469e70661aedf2b60ea5d",...]'),
                array('title', 'string', 'Title of the track', 'New title'),
                array('length', 'integer', 'Total of seconds of the track', '249'),
                array('disc_number', 'integer', 'Disc number', '1'),
                array('track_order', 'integer', 'Track order in the Album', '1'),
            ),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"_hash":"f1ca96adf6ebeec5e6f206f6525478a8","album_id":1,"title":"First Kill","length":261,"disc_number":1,"track_order":1,"artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}],"album":{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}]}}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "GET",
            "url" => "/api/v1/tracks/{track}",
            "description" => "Get a Track",
            "returns" => "Track object",
            "urlelements" => array(
                array('{track}', 'Hash of the track in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '',
            "exampleReply" => '{"_hash":"f1ca96adf6ebeec5e6f206f6525478a8","album_id":1,"title":"First Kill","length":261,"disc_number":1,"track_order":1,"artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}],"album":{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}]}}',
            "exampleBadReply" => ''
        );
        $routes[] = array(
            "methood" => "PATCH",
            "url" => "/api/v1/tracks/{track}",
            "description" => "Update Track",
            "returns" => "Track object",
            "urlelements" => array(
                array('{track}', 'Hash of the track in question')
            ),

            "bodyData" => array(
                array('title', 'string', 'Title of the track', 'New title'),
                array('length', 'integer', 'Total of seconds of the track', '249'),
                array('disc_number', 'integer', 'Disc number', '1'),
                array('track_order', 'integer', 'Track order in the Album', '1'),
            ),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"_hash":"f1ca96adf6ebeec5e6f206f6525478a8","album_id":1,"title":"First Kill","length":261,"disc_number":1,"track_order":1,"artists":[{"_hash":"7596da80a32469e70661aedf2b60ea5d","name":"Amon Amarth","image":{"id":1,"name":"Amon Amarth","file":"https:\/\/images4.alphacoders.com\/172\/172526.jpg","width":550,"height":413}}],"album":{"_hash":"e0fd4120d2126ee56a356d03a555bf97","name":"Jomsviking","release_date":"2016-03-25","images":[{"id":2,"name":"Jomsviking","file":"https:\/\/upload.wikimedia.org\/wikipedia\/en\/7\/7a\/AmonAmarthJomsviking.jpg","width":300,"height":300}]}}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );
        $routes[] = array(
            "methood" => "DELETE",
            "url" => "/api/v1/tracks/{track}",
            "description" => "Delete a track",
            "returns" => "Confirmation array",
            "urlelements" => array(
                array('{track}', 'Hash of the track in question')
            ),
            "bodyData" => array(),
            "replySuccessCode" => '200',
            "replyFailCode" => '409',
            "exampleReply" => '{"status":"success","message":"Track removed"}',
            "exampleBadReply" => '{"status":"failed","message":"xxx"}'
        );

        return $routes;
    }
}
