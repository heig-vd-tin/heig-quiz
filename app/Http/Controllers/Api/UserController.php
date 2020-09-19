<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;

class UserController extends Controller
{
    function index() {
        $users = User::all()->each(function ($item, $key) {
            $item['activities'] = url("/api/users/{$item['id']}/activities");
            $item['classrooms'] = url("/api/users/{$item['id']}/classrooms");
        });
        return [
            'count' => count($users),
            'courses' => $users,
        ];
    }

    function show($id) {
        return User::findOrFail($id);
    }

    function activities($id) {
        $activities = User::findOrFail($id)->activities()->get()->each(function ($item, $key) {
            $item['quiz'] = url("/api/quizzes/{$item['quiz_id']}");
            $item['questions'] = url("/api/quizzes/{$item['quiz_id']}/questions");
            $item['classroom'] = url("/api/classrooms/{$item['classroom_id']}");
            $item['owner'] = url("/api/users/{$item['user_id']}");
        });
        return [
            'count' => count($activities),
            'user_id' => $id,
            'activities' => $activities
        ];
    }

    function classrooms($id) {
        $classrooms = User::findOrFail($id)->classrooms()->get()->each(function ($item, $key) {
            $item['course'] = url("/api/classrooms/{$item['id']}/course");
            $item['students'] = url("/api/classrooms/{$item['id']}/students");
            $item['activities'] = url("/api/classrooms/{$item['id']}/activities");
        });
        return [
            'count' => count($classrooms),
            'classrooms' => $classrooms
        ];
    }
}
