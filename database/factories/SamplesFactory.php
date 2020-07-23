<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Samples;
use Faker\Generator as Faker;

$factory->define(Samples::class, function (Faker $faker) {
    return [
        'filename1' => $faker->name,
        'filename2' => $faker->name,
        'pairends' => $faker->randomNumber(1, true),
        'sampleLabel' => $faker->name,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
