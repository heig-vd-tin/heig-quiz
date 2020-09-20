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
use Arr;
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

    /**
     * Get all the questions ordered as the student should see them.
     * (randomized or not)
     *
     * TODO: ... Not really working
     */
    protected function get_ordered_questions($activity) {
        $questions = $activity->quiz->questions()->orderBy('id')->get()->toArray();
        if ($activity->shuffle_questions)
            return Arr::shuffle($questions, $activity->seed + Auth::id() + $activity->quiz_id);
        else
            return $questions;
    }

    /**
     * Student questions with her answers.
     */
    protected function activity_questions($activity) {
        return $activity->quiz->questions()->with(['answers' => function($query) use($activity) {
            $query->where('activity_id', $activity->id)->where('student_id', Auth::id());
        }])->get();
    }

    /**
     * Return the answered and the current question for the current activity.
     */
    function questions($id) {
        $activity = Activity::findOrFail($id);
        $questions = $this->get_ordered_questions($activity);
        $questions_count = $activity->quiz()->withCount('questions')->first()->questions_count;

        $data = [];
        foreach($this->activity_questions($activity) as $question) {
            $item = [
                'id' => $question->id,
                'name' => $question->name,
                'content' => $question->content,

                'answer' => count($question->answers) > 0 ? $question->answers[0]->answer : null
            ];

            $data[] = $item;
        };

        $answers = $activity->answers()->where('student_id', Auth::id())->orderBy('created_at')->get();

        $previous_question = max(count($answers) - 1, 0);
        $current_question = count($answers) < $questions_count ? count($answers) : null;
        $next_question = $current_question + 1 < $questions_count ? $current_questions + 1 : null;

        $response = [
            'questions' => $data,
            'current_question_id' => $current_question,
            'next_question_id' => $next_question,
            'previous_question_id' => $previous_question,

            'remaining_seconds' => $activity->duration - $activity->elapsed,

            'total_answered' => count($answers),
            'total_questions' => $questions_count,
            'percent_progression' => round(count($answers) / $questions_count * 100),

            'activity' => url("/api/activities/{$id}"),

            'current_question' => $current_question ? url("/api/activities/{$id}/questions/{$current_question}") : null,
            'next_question' => $next_question ? url("/api/activities/{$id}/questions/{$next_question}") : null,
            'previous_question' => url("/api/activities/{$id}/questions/{$previous_question}"),
        ];

        return $response;
    }

    function question(Request $request, $id, $question_id) {
        $activity = Activity::findOrFail($id);
        $question = $activity->quiz->questions()->findOrFail($question_id);

        if ($request->isMethod('post')) {
            if (!$request->answer) {
                return response([
                    'message' => "No answer given",
                    'error' => "Bad Request"
                ], 400);
            }

            //$activity->quiz->questions()
            $answer = Answer::updateOrCreate(
                [
                    'activity_id' => $activity->id,
                    'student_id' => Auth::id(),
                    'question_id' => $question_id
                ],
                [
                    'answer' => $request->answer,
                    'is_correct' => $this->validate_answer($request->answer, $question->answer)
                ]
            );
        }

        $question = $this->activity_questions($activity)[$question_id];
        return $question;

    }

    /**
     * Validate an answer
     */
    protected function validate_answer($given, $wanted) {
        return true;
    }
}
