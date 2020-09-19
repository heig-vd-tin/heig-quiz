<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Classroom;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\User;

$factory->define(Classroom::class, function (Faker $faker) {

    $teachers_id = User::where('affiliation', 'member;staff')->pluck('id')->toArray();
    return [
        'name' => Arr::random(['A', 'B', 'C']),
        'semester' => Arr::random([0, 0, 0, 0, 0, 1]),
        'year' => Arr::random([2018, 2019, 2020, 2020, 2020, 2020, 2020, 2020, 2021]),
        'teacher_id' => Arr::random($teachers_id),
    ];


});
