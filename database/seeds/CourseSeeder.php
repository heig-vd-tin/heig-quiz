<?php

use Illuminate\Database\Seeder;

use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Course::class, 10)->create();
    }
}
