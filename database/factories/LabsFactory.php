<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Labs;
use Faker\Generator as Faker;

$factory->define(Labs::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'principleInvestigator' => $faker->name,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
