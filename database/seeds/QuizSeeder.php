<?php

use Illuminate\Database\Seeder;

use App\Models\Quiz;

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

        $question = $quiz->questions()->create([
            'name' => "Canard",
            'content' => "Quelle est la différence entre un canard ?",
            'explanation' => "Un canard a deux pattes, et l'une se ressemble. Réponse absurde à une question absurde.",
            'difficulty' => 1
        ]);


    }
}
