<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Samples;
use Faker\Generator as Faker;

$factory->define(Samples::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(3, true),
        'name' => $faker->company,
        'species' => $faker->word,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
