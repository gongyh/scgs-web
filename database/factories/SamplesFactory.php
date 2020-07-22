<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Samples;
use Faker\Generator as Faker;

$factory->define(Samples::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5, true),
        'filename1' => $faker->name,
        'filename2' => $faker->name,
        'species_id' => $faker->randomNumber(5,true),
        'application_id' => $faker->randomNumber(5,true),
        'lab_id' => $faker->randomNumber(5,true),
        'pairends' => $faker->randomNumber(1, true),
        'sampleLabel' => $faker->name,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
