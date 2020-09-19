<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;

class ClassroomController extends Controller
{
    function index() {
        $classrooms = Classroom::all()->each(function ($item, $key) {
            $item['course'] = url("/api/classrooms/{$item['id']}/course");
            $item['students'] = url("/api/classrooms/{$item['id']}/students");
            $item['activities'] = url("/api/classrooms/{$item['id']}/activities");
        });
        return [
            'count' => count($classrooms),
            'classrooms' => $classrooms
        ];
    }

    function show($id) {
        return Classroom::with(['course','students','activities'])->find($id);
    }

    function teacher_classrooms($teacher_id) {
        $classrooms = User::findOrFail($teacher_id)->classrooms()->get()->each(function ($item, $key) {
            $item['course'] = url("/api/classrooms/{$item['id']}/course");
            $item['students'] = url("/api/classrooms/{$item['id']}/students");
            $item['activities'] = url("/api/classrooms/{$item['id']}/activities");
        });
        return [
            'count' => count($classrooms),
            'classrooms' => $classrooms
        ];
    }

    function students($id) {
        $students = Classroom::with('students')->findOrFail($id)->students->each(function ($item, $key) {
            $item['user'] = url('/api/users/' . $item['user_id']);
        });
        return [
            'count' => count($students),
            'classroom' => $id,
            'students' => $students
        ];
    }

    function activities($id) {
        $activities = Classroom::with('activities')->findOrFail($id)->activities->each(function ($item, $key) {
        });
        return [
            'count' => count($activities),
            'classroom' => $id,
            'activities' => $activities
        ];
    }

    function course($id) {
        $course = Classroom::with('course')->findOrFail($id)->course;
        return $course;
    }
}
