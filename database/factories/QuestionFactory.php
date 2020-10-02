<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Question;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'validation' => '42',
            'difficulty' => Arr::random(['easy', 'medium', 'hard', 'insane']),
            'explanation' => $this->faker->paragraph()
        ];
    }
}
