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

$factory->define(App\v1\Models\Artists::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'image_id' => 1
    ];
});