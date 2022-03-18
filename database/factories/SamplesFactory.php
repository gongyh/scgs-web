<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Samples;
use Faker\Generator as Faker;

$factory->define(Samples::class, function (Faker $faker) {
    return [
        'filename1' => $faker->name,
        'filename2' => $faker->name,
        'pairends' => $faker->randomDigitNotNull,
        'species_id' => $faker->randomDigitNotNull,
        'projects_id' => $faker->randomDigitNotNull,
        'applications_id' => $faker->randomDigitNotNull,
        'sampleLabel' => $faker->name,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
