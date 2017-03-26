<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected $artist;
    protected $genres = array();

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $seed = array(
            array(
                "name" => 'Amon Amarth',
                "image" => 'http://www.metal-archives.com/images/1/5/0/150_photo.jpg',
                "albums" => array(
                    array(
                        "title" => "Jomsviking",
                        "release_date" => "2016-03-25",
                        "image" => "https://upload.wikimedia.org/wikipedia/en/thumb/7/7a/AmonAmarthJomsviking.jpg/220px-AmonAmarthJomsviking.jpg",
                        "genre" => "Melodic Death Metal",
                        "tracks" => array(
                            array("First Kill", (4 * 60 + 21), 1),
                            array("Wanderer", (4 * 60 + 42), 1),
                            array("On a Sea of Blood", (4 * 60 + 4), 1),
                            array("One Against All", (3 * 60 + 37), 1),
                            array("Raise Your Horns", (4 * 60 + 23), 1),
                            array("The Way of Vikings", (5 * 60 + 11), 1),
                            array("At Dawn’s First Light", (3 * 60 + 50), 1),
                            array("One Thousand Burning Arrows", (5 * 60 + 49), 1),
                            array("Vengeance Is My Name", (4 * 60 + 41), 1),
                            array("A Dream That Cannot Be", (4 * 60 + 22), 1),
                            array("Back on Northern Shores", (7 * 60 + 8), 1),
                        )
                    ),
                    array(
                        "title" => "Deceiver of the Gods",
                        "release_date" => "2013-06-25",
                        "image" => "https://upload.wikimedia.org/wikipedia/en/thumb/2/2f/DeceiveroftheGodsAmonAmarth.jpg/220px-DeceiveroftheGodsAmonAmarth.jpg",
                        "genre" => "Melodic Death Metal",
                        "tracks" => array(
                            array("Deceiver of the Gods", (4 * 60 + 19), 1),
                            array("As Loke Falls", (4 * 60 + 38), 1),
                            array("Father of the Wolf", (4 * 60 + 19), 1),
                            array("Shape Shifter", (4 * 60 + 2), 1),
                            array("Under Siege", (6 * 60 + 17), 1),
                            array("Blood Eagle", (3 * 60 + 15), 1),
                            array("We Shall Destroy", (4 * 60 + 25), 1),
                            array("Hel", (4 * 60 + 9), 1),
                            array("Coming of the Tide", (4 * 60 + 16), 1),
                            array("Warriors of the North", (8 * 60 + 12), 1),
                        )
                    )
                ),
            ),
            array(
                "name" => "Caravan Palace",
                "image" => "http://floodmagazine.com/wp-content/uploads/2015/10/Caravan_Palace-2015-Antoine_Delaporte.jpg",
                "albums" => array(
                    array(
                        "title" => "Caravan Palace",
                        "release_date" => "2008-10-20",
                        "image" => "https://upload.wikimedia.org/wikipedia/en/thumb/1/13/Caravan_Palace.png/220px-Caravan_Palace.png",
                        "genre" => "Electro Swing",
                        "tracks" => array(
                            array("Dragons", (4 * 60 + 5), 1),
                            array("Star Scat", (3 * 60 + 50), 1),
                            array("Ended With the Night", (5 * 60), 1),
                            array("Jolie Coquine", (3 * 60 + 46), 1),
                            array("Oooh", (1 * 60 + 49), 1),
                            array("Suzy", (4 * 60 + 67), 1),
                            array("Je MAmuse", (3 * 60 + 34), 1),
                            array("Violente Valse", (3 * 60 + 35), 1),
                            array("Brotherswing", (3 * 60 + 42), 1),
                            array("LEnvol", (3 * 60 + 46), 1),
                            array("Sofa", (51), 1),
                            array("Bambous", (3 * 60 + 14), 1),
                            array("Lazy Place", (3 * 60 + 57), 1),
                            array("We Can Dance", (4 * 60 + 23), 1),
                            array("La Caravane", (5 * 60 + 5), 1),
                        )
                    )
                ),
            ),
            array(
                "name" => "Michael Jackson",
                "image" => "http://www.bransonshowtickets.com/media/3235117784_o.jpg",
                "albums" => array(
                    array(
                        "title" => "Thriller",
                        "release_date" => "1982-11-30",
                        "image" => "https://upload.wikimedia.org/wikipedia/en/thumb/5/55/Michael_Jackson_-_Thriller.png/220px-Michael_Jackson_-_Thriller.png",
                        "genre" => "Pop",
                        "tracks" => array(
                            array("Wanna Be Startin Somethin", (6 * 60 + 4), 1),
                            array("Baby Be Mine", (4 * 60 + 21), 1),
                            array("The Girl Is Mine", (3 * 43), 1),
                            array("Thriller", (5 * 60 + 58), 1),
                            array("Beat It", (4 * 60 + 18), 2),
                            array("Billie Jean", (4 * 60 + 54), 2),
                            array("Human Nature", (4 * 60 + 7), 2),
                            array("P.Y.T. (Pretty Young Thing)", (3 * 60 + 58), 2),
                            array("The Lady in My Life", (4 * 60 + 59), 2),
                        )
                    )
                ),
            ),
        );

        foreach ($seed as $artist) {
            $image = $artist["image"];
            $createdImage = factory(App\Models\Images::class)->create(["file" => $image, "name" => $artist["name"]]);

            $createdArtist = factory(App\Models\Artists::class)->create([
                'name' => $artist["name"],
                'image_id' => $createdImage->id
            ]);

            foreach ($artist["albums"] as $album) {
                $createdGenre = App\Models\Genres::where('title', $album["genre"])->first();
                if ($createdGenre == null) {
                    $createdGenre = factory(App\Models\Genres::class)->create(["title" => $album["genre"]]);
                }

                $createdImageAlbum = factory(App\Models\Images::class)->create(["file" => $album["image"], "name" => $album["title"]]);

                $createdAlbum = factory(App\Models\Albums::class)->create(["name" => $album["title"], "release_date" => $album["release_date"]]);

                foreach ($album["tracks"] as $index => $track) {
                    factory(App\Models\Tracks::class)->create([
                        "album_id" => $createdAlbum->id,
                        "track_order" => ((int)$index + 1),
                        "title" => $track[0],
                        "length" => $track[1],
                        "disc_number" => $track[2],
                    ])->each(function ($createdTrack) use ($createdAlbum, $createdArtist) {
                        $createdTrack->artists()->attach($createdArtist->id);
                    });
                }

                $createdAlbum->artists()->attach($createdArtist->id);
                $createdAlbum->genres()->attach($createdGenre->id);
                $createdAlbum->images()->attach($createdImageAlbum->id);
            }
        }
    }
}
