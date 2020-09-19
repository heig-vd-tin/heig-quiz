<?php

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activities')->insert([
            'quiz_id' => 1,
            'roster_id' => 1,
            'duration' => 60,
            'state' => 'in_progress',
            'user_id' => 1
        ]);

        DB::table('answers')->insert([
            'activity_id' => 1,
            'student_id' => 1,
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => '0'
        ]);

        DB::table('answers')->insert([
            'activity_id' => 1,
            'student_id' => 1,
            'question_id' => '2',
            'answer' => '???',
            'is_correct' => '0'
        ]);

        DB::table('answers')->insert([
            'activity_id' => 1,
            'student_id' => '2',
            'question_id' => '4',
            'answer' => '???',
            'is_correct' => 1
        ]);

        DB::table('answers')->insert([
            'activity_id' => 1,
            'student_id' => '2',
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => 1
        ]);

        DB::table('answers')->insert([
            'activity_id' => 1,
            'student_id' => '2',
            'question_id' => 1,
            'answer' => '???',
            'is_correct' => '0'
        ]);

        factory(Activity::class, 20)->create();
    }
}
