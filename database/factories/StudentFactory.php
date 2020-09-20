<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Student;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'orientation' => Arr::random(['EEM', 'EAI', 'EN']),
            'type' => Arr::random(['PT', 'TP']),
        ];
    }
}
