<?php

use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Arr;


class QuizSeeder extends Seeder
{
    public function run()
    {
        $quiz = Quiz::create([
            'name' => 'Quiz-00 Mes Débuts'
        ]);

        $quiz->questions()->attach([1,2,3]);


        $questions = Question::all();
        $k = 0;

        factory(Quiz::class, 10)->create()->each(function ($quiz) {
            $count = Arr::random(['5', '10', '10', '10', '15']);
            for($i = 0; $i < $count; $i++) {
                $question = factory(Question::class)->make();
                $question->save();
                $quiz->questions()->attach($question);
            }
        });
    }
}
