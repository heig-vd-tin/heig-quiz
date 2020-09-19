<?php

use Illuminate\Database\Seeder;

use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Course::create([
            'name' => 'Info1',
            'department' => 'tin'
        ]);

        Course::create([
            'name' => 'Info2',
            'department' => 'tin'
        ]);
    }
}
