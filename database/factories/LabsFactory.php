<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Labs;
use Faker\Generator as Faker;

$factory->define(Labs::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5, true),
        'name' => $faker->company,
        'login' => $faker->randomNumber(5, true),
        'password' => $faker->randomNumber(5, true),
        'principleInvestigator' => $faker->name,
        'institution_id' => $faker->randomNumber(5, true),
        'updated_at' => now(),
        'created_at' => now()
    ];
});
