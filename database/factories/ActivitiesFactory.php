<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Activity;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Roster;

$factory->define(Activity::class, function (Faker $faker) {

    $teacher_id = User::where('affiliation', 'member;staff')->inRandomOrder()->limit(1)->get()[0]->id;
    $quiz_id = Quiz::inRandomOrder()->limit(1)->get()[0]->id;
    $roster_id = Roster::inRandomOrder()->limit(1)->get()[0]->id;
    return [
        'quiz_id' => $quiz_id,
        'roster_id' => $roster_id,
        'user_id' => $teacher_id,
        'duration' => Arr::random([20, 60, 600, 600, 600, 600, 600, 600, 1000 ]),
        'state' => 'ready',
        'shuffle_questions' => Arr::random([0, 1]),
        'shuffle_propositions' => Arr::random([0, 1]),
        'seed' => $faker->randomNumber()
    ];
});
