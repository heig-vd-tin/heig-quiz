<?php

use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\Question;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quiz = Quiz::create([
            'name' => 'Quiz 00'
        ]);

        $quiz->questions()->attach([1,2,3]);

    }
}
