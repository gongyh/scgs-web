<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Projects;
use Faker\Generator as Faker;

$factory->define(Projects::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'doi' => $faker->numerify('doi #####'),
        'description' => $faker->sentence(5, true),
        'labs_id' => $faker->randomDigitNotNull,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
