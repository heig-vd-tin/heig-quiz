<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Quiz;
use App\Models\User;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition()
    {
        $teachers_id = User::where('affiliation', 'not like' '%student%')->pluck('id')->toArray();
        return [
            'name' => $this->faker->sentence(4),
            'user_id' => Arr::random($teachers_id),
        ];
    }
}
