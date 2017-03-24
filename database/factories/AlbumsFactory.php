<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 23/03/2017
 * Time: 23:50
 */

$factory->define(App\Models\Albums::class, function (Faker\Generator $faker) {

    return [
        'name' => ucwords($faker->word.' '.$faker->word.' '.$faker->word),
        '_hash' => md5(uniqid(rand()+time(), true)),
        'release_date' => $faker->dateTime
    ];
});
