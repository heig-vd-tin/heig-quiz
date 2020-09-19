<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Course;

class CourseController extends Controller
{
    function index() {
        $courses = Course::with('classrooms')->get();
        return [
            'count' => count($courses),
            'courses' => $courses,
            'api' => [
            ]
        ];
    }
}
