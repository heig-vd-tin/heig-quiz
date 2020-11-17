<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Student;

class StudentController extends Controller
{
    function index() {
        $students = Student::all()->each(function ($item, $key) {
            $item['user'] = url('/api/users/' . $item['user_id']);
        });

        return [
            'count' => count($students),
            'students' => $students,
            'api' => [
            ]
        ];
    }

    function getStudents() {
        $students = Student::with('user')->get()->all();

        return [
            'count' => count($students),
            'students' => $students,
        ];
    }
}
