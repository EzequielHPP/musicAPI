<?php
/**
 * Created by PhpStorm.
 * User: ezequielpereira
 * Date: 25/03/2017
 * Time: 19:51
 */


$factory->define(App\Models\Users::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('secret')
    ];
});