<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Labs;
use Faker\Generator as Faker;

$factory->define(Labs::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(3, true),
        'name' => $faker->company,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
