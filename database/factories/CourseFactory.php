<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Course;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'name' => Arr::random(['Info1', 'Info1', 'Info1', 'Info2', 'Info2', 'ProgOO', 'VisIndus', 'TraiSignAp']),
            'department' => 'TIN',
        ];
    }
}
