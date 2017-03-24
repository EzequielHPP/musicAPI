<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 23/03/2017
 * Time: 23:52
 */


$factory->define(App\Models\Tracks::class, function (Faker\Generator $faker) {

    return [
        'album_id' => 1,
        'title' => $faker->name,
        '_hash' => md5(uniqid(rand()+time(), true)),
        'length' => $faker->numberBetween(10,200),
        'disc_number' => 1,
        'track_order' => $faker->numberBetween(1,10)
    ];
});