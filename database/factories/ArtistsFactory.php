<?php

/*
|--------------------------------------------------------------------------
| Artists Factories
|--------------------------------------------------------------------------
|
| On this factory an Artist
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Artists::class, function (Faker\Generator $faker) {

    return [
        'name' => ucwords($faker->word.' '.$faker->word),
        '_hash' => md5(uniqid(rand()+time(), true)),
        'image_id' => 1
    ];
});