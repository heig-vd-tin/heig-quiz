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

        $question = $quiz->question()->create([
            'name' => "Canard",
            'content' => "Quelle est la différence entre un canard ?",
            'answer' => "Un canard a deux pattes, et l'une se ressemble. Réponse absurde à une question absurde.",
            'difficulty' => 1,
            'explanation' => ''
        ]);

        $quiz->question()->attach([1,2]);

    }
}
