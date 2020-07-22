<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Projects;
use Faker\Generator as Faker;

$factory->define(Projects::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5, true),
        'labID' => $faker->randomNumber(5, true),
        'name' => $faker->company,
        'doi' => $faker->numerify('doi #####'),
        'description' => $faker->sentence(5, true),
        'updated_at' => now(),
        'created_at' => now()
    ];
});
