<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Application;
use Faker\Generator as Faker;

$factory->define(Application::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence(5, true),
        'updated_at' => now(),
        'created_at' => now()
    ];
});
