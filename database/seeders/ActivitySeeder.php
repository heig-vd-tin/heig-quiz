<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Answer;
use DB;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        Activity::create([
            'quiz_id' => 1,
            'roster_id' => 1,
            'duration' => 120,
            'user_id' => 1,
            'started_at' => '2020-09-01T14:58:05',
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => 1,
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => '0'
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => 1,
            'question_id' => '2',
            'answer' => '???',
            'is_correct' => '0'
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => '2',
            'question_id' => '4',
            'answer' => '???',
            'is_correct' => 1
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => '2',
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => 1
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => '2',
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => '0'
        ]);

        Activity::factory()->count(20)->create();
    }
}
