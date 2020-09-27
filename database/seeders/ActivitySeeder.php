<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Answer;
use App\Models\User;
use DB;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $activity = Activity::create([
            'quiz_id' => 1,
            'roster_id' => 1,
            'duration' => 120,
            'user_id' => 1,
            'started_at' => '2020-09-01T14:58:05',
            'opened_at' => '2020-09-01T15:30:00',
            'ended_at' => '2020-09-01T15:40:00',
        ]);

        $student = User::find(3)->student;

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 2,
            'answer' => '???',
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 3,
            'answer' => '???',
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 4,
            'answer' => '???',
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 5,
            'answer' => '???',
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 6,
            'answer' => '42',
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 7,
            'answer' => '42',
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'question_id' => 8,
            'answer' => '42',
            'is_correct' => true
        ]);

        Activity::factory()->count(20)->create();
    }
}
