<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;

class UserController extends Controller
{
    function index() {
        $users = User::all()->each(function ($item, $key) {
            $item['activities'] = url("/api/users/{$item['id']}/activities");
            $item['rosters'] = url("/api/users/{$item['id']}/rosters");
        });
        return [
            'count' => count($users),
            'courses' => $users,
        ];
    }

    function show($id) {
        return User::findOrFail($id);
    }

    function me() {
        return $this->show(Auth::id());
    }

    function activities($id) {
        $activities = User::findOrFail($id)->activities()->get()->each(function ($item, $key) {
            $item['quiz'] = url("/api/quizzes/{$item['quiz_id']}");
            $item['questions'] = url("/api/quizzes/{$item['quiz_id']}/questions");
            $item['roster'] = url("/api/rosters/{$item['roster_id']}");
            $item['owner'] = url("/api/users/{$item['user_id']}");
            $item['activity'] = url("/api/activities/{$item['id']}");
        });
        return [
            'count' => count($activities),
            'user_id' => $id,
            'activities' => $activities
        ];
    }

    function rosters($id) {
        $rosters = User::findOrFail($id)->rosters()->get()->each(function ($item, $key) {
            $item['course'] = url("/api/rosters/{$item['id']}/course");
            $item['students'] = url("/api/rosters/{$item['id']}/students");
            $item['activities'] = url("/api/rosters/{$item['id']}/activities");
        });
        return [
            'count' => count($rosters),
            'rosters' => $rosters
        ];
    }
}
