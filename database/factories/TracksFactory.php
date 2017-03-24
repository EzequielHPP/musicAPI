<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 23/03/2017
 * Time: 23:52
 */


$factory->define(App\v1\Models\Tracks::class, function (Faker\Generator $faker) {

    return [
        'album_id' => 1,
        'title' => $faker->name,
        'length' => $faker->numberBetween(10,200),
        'disc_number' => $faker->numberBetween(1,2),
        'track_order' => $faker->numberBetween(1,10)
    ];
});