<?php

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Student;

class ClassroomSeeder extends Seeder
{
    public function run()
    {
        // Get all students
        $students = Student::all();

        // Generate the courses
        foreach (Course::all() as $course) {
            $classroom = factory(App\Models\Classroom::class)->make();
            $classroom->name = 'A';
            $classroom->course_id = $course->id;
            $classroom->save();

            $classroom = factory(App\Models\Classroom::class)->make();
            $classroom->name = 'B';
            $classroom->course_id = $course->id;
            $classroom->save();
        }

        // TODO: Add students in classrooms...
    }
}
