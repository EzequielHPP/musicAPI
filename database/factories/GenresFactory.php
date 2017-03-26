<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 23/03/2017
 * Time: 23:51
 */

$factory->define(App\Models\Genres::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->name,
        '_hash' => md5(uniqid(rand()+time(), true))
    ];
});