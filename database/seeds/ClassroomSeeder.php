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
        $courses = Course::all();
        $studentsCount = count($students);
        $studentPerClass = $studentsCount / count($courses) / 2;
        $k = 0;

        // Generate the courses
        foreach (Course::all() as $course) {
            $classroom = factory(App\Models\Classroom::class)->make();
            $classroom->name = 'A';
            $classroom->course_id = $course->id;
            $classroom->save();
            for($i = 0; $i < $studentPerClass && $k < $studentsCount; $i++) {
                $classroom->students()->attach($students[$k++]);
            }

            $classroom = factory(App\Models\Classroom::class)->make();
            $classroom->name = 'B';
            $classroom->course_id = $course->id;
            $classroom->save();
            for($i = 0; $i < $studentPerClass && $k < $studentsCount; $i++) {
                $classroom->students()->attach($students[$k++]);
            }
        }
    }
}
