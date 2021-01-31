<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Balance;
use Faker\Generator as Faker;

$factory->define(Balance::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 999),
        'balance' => $faker->numberBetween(1, 1000),
    ];
});
