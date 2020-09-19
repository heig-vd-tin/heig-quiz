<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Quiz;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$factory->define(Quiz::class, function (Faker $faker) {

    return [
        'name' => $faker->sentence(),
    ];
});
