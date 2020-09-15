<?php

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Classroom;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'orientation' => 'AA',
            'type' => 'TP',
            'user_id' => '2'
        ]);

        DB::table('students')->insert([
            'orientation' => 'AA',
            'type' => 'TP',
            'user_id' => '3'
        ]);

        Classroom::find(1)->students()->attach([1,2]);
    }
}
