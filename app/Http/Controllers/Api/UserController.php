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

    }
}
