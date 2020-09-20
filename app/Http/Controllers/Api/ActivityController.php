<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Activity;
use App\Models\Student;
use App\Models\Roster;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use Auth;

class ActivityController extends Controller
{
    function index() {
        $activities = Activity::all()->each(function ($item, $key) {
            $item['activity'] = url("/api/activities/{$item['id']}");
            $item['quiz'] = url("/api/quizzes/{$item['quiz_id']}");
            $item['roster'] = url("/api/rosters/{$item['roster_id']}");
            $item['questions'] = url("/api/quiz/{$item['quiz_id']}/questions");
        });
        return [
            'count' => count($activities),
            'activities' => $activities
        ];
    }

    function show($id) {
        $activity = Activity::findOrFail($id);
        $activity['quiz'] = url("/api/quizzes/{$activity['quiz_id']}");
        $activity['roster'] = url("/api/rosters/{$activity['roster_id']}");
        $activity['questions'] = url("/api/quizzes/{$activity['quiz_id']}/questions");

        if ($activity->hidden)
            $activity['@show'] = url("/api/quizzes/{$activity['quiz_id']}/show");
        else
            $activity['@hide'] = url("/api/quizzes/{$activity['quiz_id']}/hide");

        if (!$activity->started && !$activity->completed)
            $activity['@start'] = url("/api/quizzes/{$activity['quiz_id']}/start");

        return $activity;
    }

    function roster($id) {
        $roster = Activity::findOrFail($id)->roster()->get()->each(function ($item, $key) {
            $item['course'] = url("/api/courses/{$item['course_id']}");
            $item['teacher'] = url("/api/users/{$item['teacher_id']}");
        });
        return $roster;
    }

    function rosterActivities($roster) {
        $activities = Roster::with('activities')->findOrFail($roster)->activities->each(function ($item, $key) {
            $item['quiz'] = url("/api/quizzes/{$item['quiz_id']}");
            $item['roster'] = url("/api/rosters/{$item['roster_id']}");
            $item['questions'] = url("/api/quizzes/{$item['quiz_id']}/questions");
        });
        return [
            'count' => count($activities),
            'roster' => $roster,
            'activities' => $activities
        ];
    }

    function start($id) {
        $activity = Activity::findOrFail($id);
        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Only the owner of an activity start it",
                'error' => "Unauthorized"
            ], 403);
        }

        if ($activity->hidden) {
            return response([
                'message' => "Cannot start an hidden activity",
                'error' => "Bad Request"
            ], 400);
        }

        if ($activity->completed) {
            return response([
                'message' => "Cannot restart an activity",
                'error' => "Bad Request"
            ], 400);
        }

        if ($activity->started) {
            return response([
                'message' => "Activity activity started",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->started_at = Carbon::now();
        $activity->save();
    }

    function set_hidden($id) {
        $activity = Activity::findOrFail($id);
        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Only the owner of an activity can change the visibility",
                'error' => "Unauthorized"
            ], 403);
        }

        if ($activity->hidden) {
            return response([
                'message' => "Activity already hidden",
                'error' => "Bad Request"
            ], 400);
        }

        if ($activity->started) {
            return response([
                'message' => "Cannot hide an on going activity",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->hidden = true;
        $activity->save();
    }

    function set_visible($id) {
        $activity = Activity::findOrFail($id);
        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Only the owner of an activity can change the visibility",
                'error' => "Unauthorized"
            ], 403);
        }

        if (!$activity->hidden) {
            return response([
                'message' => "Activity already visible",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->hidden = false;
        $activity->save();
    }

    function create(Request $request, $quiz_id) {
        $data = $request->validate([
            'duration' => 'min:10',
            'roster_id' => 'required|exists:rosters,id',
            'shuffle_questions' => 'boolean',
            'shuffle_propositions' => 'boolean',
            'seed' => 'numeric',
        ]);

        if (Roster::findOrFail($request->input('roster_id'))->teacher_id != Auth::id()) {
            return response([
                'message' => "Only the roster's teacher can create an activity",
                'error' => "Bad Request"
            ], 400);
        }

        if (Roster::findOrFail($request->input('roster_id'))->teacher_id != Auth::id()) {
            return response([
                'message' => "Only the roster's teacher can create an activity",
                'error' => "Bad Request"
            ], 400);
        }

        Quiz::findOrFail($quiz_id)->activities()->create([
            'user_id' => Auth::id(),
            'roster_id' => $request->roster_id,
            'duration' => $request->input('duration', 600),
            'shuffle_questions' => $request->input('shuffle_questions', false),
            'shuffle_propositions' => $request->input('shuffle_propositions', false),
            'seed' => input('seed', random_int(0, 4294967295))
        ]);
    }

    function get_random_question($activity, $id) {
        $questions = $activity->quiz->questions()->orderBy('id')->get();
        return Arr::shuffle($questions, $activity->seed + Auth::id() + $activity->quiz_id)[$id];
    }

    function question(Request $request, $id, $question_id) {
        $activity = Activity::findOrFail($id);
        $question = null;
        return [
            'question' => $question,
            'remaining_seconds' => $activity->duration - $activity->elapsed,
            'next' => url("/api/activities/{$id}/questions/{$next_question_id}"),
            'previous' => url("/api/activities/{$id}/questions/{$previous_question_id}")
        ];
    }
}
