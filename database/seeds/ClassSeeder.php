<?php

use Illuminate\Database\Seeder;
use App\Models\Classroom;

class ClassSeeder extends Seeder
{
    public function run()
    {
        Classroom::create([
            'number' => '2',
            'course_id' => '1',
            //'name' => 'Info1',
            'semester' => '1',
            'year' => '2020'
        ]);
    }
}
