<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classroom;
use App\Models\Course;

class ClassroomController extends Controller
{
    function getClass($id) {
        return Classroom::with(['course','students'])->find(1);
    }

    function getCourse($id) {
        return Course::with('classroom')->find(1);
    }    
}
