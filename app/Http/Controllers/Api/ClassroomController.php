<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Roster;
use App\Models\Course;
use App\Models\User;

class RosterController extends Controller
{
    function index() {
        $rosters = Roster::all()->each(function ($item, $key) {
            $item['course'] = url("/api/rosters/{$item['id']}/course");
            $item['students'] = url("/api/rosters/{$item['id']}/students");
            $item['activities'] = url("/api/rosters/{$item['id']}/activities");
        });
        return [
            'count' => count($rosters),
            'rosters' => $rosters
        ];
    }

    function show($id) {
        return Roster::with(['course','students','activities'])->find($id);
    }

    function teacher_rosters($teacher_id) {
        $rosters = User::findOrFail($teacher_id)->rosters()->get()->each(function ($item, $key) {
            $item['course'] = url("/api/rosters/{$item['id']}/course");
            $item['students'] = url("/api/rosters/{$item['id']}/students");
            $item['activities'] = url("/api/rosters/{$item['id']}/activities");
        });
        return [
            'count' => count($rosters),
            'rosters' => $rosters
        ];
    }

    function students($id) {
        $students = Roster::with('students')->findOrFail($id)->students->each(function ($item, $key) {
            $item['user'] = url('/api/users/' . $item['user_id']);
        });
        return [
            'count' => count($students),
            'roster' => $id,
            'students' => $students
        ];
    }

    function activities($id) {
        $activities = Roster::with('activities')->findOrFail($id)->activities->each(function ($item, $key) {
        });
        return [
            'count' => count($activities),
            'roster' => $id,
            'activities' => $activities
        ];
    }

    function course($id) {
        $course = Roster::with('course')->findOrFail($id)->course;
        return $course;
    }
}
