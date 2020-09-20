<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Student;
use App\Models\Roster;

class RosterSeeder extends Seeder
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
            $roster = Roster::factory()->make();
            $roster->name = 'A';
            $roster->course_id = $course->id;
            $roster->save();
            for($i = 0; $i < $studentPerClass && $k < $studentsCount; $i++) {
                $roster->students()->attach($students[$k++]);
            }

            $roster = Roster::factory()->make();
            $roster->name = 'B';
            $roster->course_id = $course->id;
            $roster->save();
            for($i = 0; $i < $studentPerClass && $k < $studentsCount; $i++) {
                $roster->students()->attach($students[$k++]);
            }
        }
    }
}
