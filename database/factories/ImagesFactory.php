<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 23/03/2017
 * Time: 23:51
 */

$factory->define(App\v1\Models\Images::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'file' => $faker->unique()->words(2, true),
        'width' => $faker->numberBetween(100,500),
        'height' => $faker->numberBetween(100,500)
    ];
});