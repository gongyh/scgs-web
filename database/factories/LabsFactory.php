<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Labs;
use Faker\Generator as Faker;

$factory->define(Labs::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'principleInvestigator' => $faker->name,
        'institutions_id' => $faker->randomDigitNotNull,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
