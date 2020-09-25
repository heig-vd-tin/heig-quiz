<?php
namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $gender = Arr::random([0 /* male */, 1 /* female */]);
        $firstname = $this->faker->firstName(['male', 'female'][$gender]);
        $lastname = $this->faker->lastName;
        $email = strtolower($firstname) . '.' . strtolower($lastname) . '@heig-vd.com';
        return [
            'name' => "$firstname $lastname",
            'email' => $this->faker->unique()->safeEmail,
            'unique_id' => $this->faker->unique()->randomNumber(),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => 'shibboleth',
            'gender' => $gender,
            'affiliation' => 'member;student',
            'remember_token' => Str::random(10),
            'api_token' => Str::random(60),
        ];
    }
}
