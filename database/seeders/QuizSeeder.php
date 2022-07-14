<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Arr;

class QuizSeeder extends Seeder
{
    public function run()
    {
        $quiz = Quiz::create([
            'name' => 'Quiz-00 Mes Débuts',
            'user_id' => 1
        ]);

        $quiz->questions()->attach([9]);

        
        $questions = Question::all();
        $k = 0;

        Quiz::factory()->count(10)->create()->each(function ($quiz) {
            $count = Arr::random(['5', '10', '10', '10', '15']);
            for($i = 0; $i < $count; $i++) {
                $question = Question::factory()->make();
                $question->save();
                $quiz->questions()->attach($question);
            }
        });
    }
}
