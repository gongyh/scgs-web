<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Application;
use App\Applications;
use Faker\Generator as Faker;

$factory->define(Applications::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence(5, true),
        'updated_at' => now(),
        'created_at' => now()
    ];
});
