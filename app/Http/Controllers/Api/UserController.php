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
        $activities = User::findOrFail($id)->activities()->get();
        return [
            'count' => count($activities),
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
