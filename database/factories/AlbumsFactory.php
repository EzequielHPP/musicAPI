<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 23/03/2017
 * Time: 23:50
 */

$factory->define(App\v1\Models\Albums::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'release_date' => $faker->dateTime
    ];
});
