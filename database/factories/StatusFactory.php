<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Status;
use Faker\Generator as Faker;

$factory->define(Status::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(5, true),
        'runId' => $faker->randomNumber(5, true),
        'status' => $faker->lexify('Hello ???'),
        'started' => now(),
        'finished' => now(),
        'updated_at' => now(),
        'created_at' => now()
    ];
});
