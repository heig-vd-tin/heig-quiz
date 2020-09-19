<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Student;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


$factory->define(Student::class, function (Faker $faker) {

    return [
        'orientation' => Arr::random(['EEM', 'EAI', 'EN']),
        'type' => Arr::random(['PT', 'TP']),
    ];
});
