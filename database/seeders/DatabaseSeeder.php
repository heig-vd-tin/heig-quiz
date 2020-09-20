<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,

            CourseSeeder::class,
            RosterSeeder::class,

            QuestionSeeder::class,
            KeywordSeeder::class,
            KeywordQuestionSeeder::class,

            QuizSeeder::class,

            ActivitySeeder::class
        ]);
    }
}
