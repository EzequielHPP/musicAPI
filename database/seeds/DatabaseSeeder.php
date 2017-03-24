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

        factory(App\v1\Models\Images::class, 10)->create()->each(function ($image) {

            // Create artist
            factory(\App\v1\Models\Artists::class, 1)->create([
                'image_id' => $image->id
            ])->each(function ($art) {
                // Save the artist into a global variable so it can be accessed afterwards
                $this->artist = $art;

                // Create genres
                factory(\App\v1\Models\Genres::class, rand(1, 3))->create()->each(function ($genre) {
                    $this->genres[] = $genre->id;
                });

                // Create albums (between 1 and 4)
                factory(\App\v1\Models\Albums::class, rand(1, 4))->create()->each(function ($album) {
                    $album->artists()->attach($this->artist->id);

                    // Attach the created genres to the album
                    foreach($this->genres as $genreId) {
                        $album->genres()->attach($genreId);
                    }

                    // Create album cover images
                    factory(App\v1\Models\Images::class, rand(1,3))->create()->each(function ($cover) use($album) {
                        $album->images()->attach($cover->id);
                    });

                    $nextTrack = 1;

                    // Create the tracks for this album
                    factory(\App\v1\Models\Tracks::class, rand(2, 10))->make()->each(function ($f) use ($nextTrack, $album) {
                        $f->track_order = $nextTrack;
                        $f->album_id = $album->id;
                        $f->save();
                    });
                });
            });
        });
    }
}
