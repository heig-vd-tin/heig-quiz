<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Transformer\RosterTransformer;
use App\Transformer\StudentTransformer;

use App\Models\Roster;
use App\Models\User;
use Auth;

class RosterController extends Controller
{
    function index(Request $request) {
        if ($request->owned)
            $rosters = Roster::where('teacher_id', Auth::id())->get();
        else
            $rosters = Roster::all();

        return fractal($rosters, new RosterTransformer(true))->toArray();
    }

    function owned() {
        $request = request();
        $request->owned = true;
        return $this->index($request);
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
        $students = Roster::findOrFail($id)->students;

        return fractal($students, new StudentTransformer())->toArray();
    }

    function course($id) {
        $course = Roster::with('course')->findOrFail($id)->course;
        return $course;
    }

}
