<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Institutions;
use Faker\Generator as Faker;

$factory->define(Institutions::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5, true),
        'name' => $faker->address,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
