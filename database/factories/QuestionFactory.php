<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Question;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$factory->define(Question::class, function (Faker $faker) {

    return [
        'name' => $faker->sentence(),
        'content' => $faker->paragraph(),
        'answer' => '42',
        'difficulty' => Arr::random(['easy', 'medium', 'hard', 'insane']),
        'explanation' => $faker->paragraph()
    ];
});
