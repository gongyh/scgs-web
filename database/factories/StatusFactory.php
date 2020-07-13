<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Status;
use Faker\Generator as Faker;

$factory->define(Status::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(3, true),
        'run_samples' => $faker->company,
        'updated_at' => now(),
        'created_at' => now()
    ];
});
