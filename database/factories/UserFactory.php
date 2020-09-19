<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $gender = Arr::random([0 /* male */, 1 /* female */]);
    $firstname = $faker->firstName(['male', 'female'][$gender]);
    $lastname = $faker->lastName;
    $email = strtolower($firstname) . '.' . strtolower($lastname) . '@heig-vd.com';
    return [
        'name' => "$firstname $lastname",
        'email' => $faker->unique()->safeEmail,
        'unique_id' => $faker->unique()->randomNumber(),
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => 'shibboleth',
        'gender' => $gender,
        'affiliation' => 'member;student',
        'remember_token' => Str::random(10),
    ];
});
