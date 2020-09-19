<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


$factory->define(Course::class, function (Faker $faker) {

    return [
        'name' => Arr::random(['Info1', 'Info1', 'Info1', 'Info2', 'Info2', 'ProgOO', 'VisIndus', 'TraiSignAp']),
        'department' => 'TIN',
    ];
});
