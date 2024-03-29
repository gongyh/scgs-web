<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Institutions;
use Faker\Generator as Faker;

$factory->define(Institutions::class, function (Faker $faker) {
    return [
        'name' => $faker->address,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
