<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Activity;
use App\Models\Roster;
use App\Models\Answer;
use App\Models\Quiz;
use Validator;
use Arr;
use Auth;
use Log;

use App\Transformer\ActivityTransformer;
use App\Transformer\QuestionTransformer;

class ActivityController extends Controller
{
    /**
     * Get all activities for teachers and participating in activites for students.
     */
    function index(Request $request) {
        $activities = Activity::query();

        if ($request->user()->isStudent())
            $activities = $request->user()->student->activities()->where('hidden', false);

        if ($request->user()->isTeacher() && $request->owned)
            $activities = $request->user()->activities();

        if (($roster_id = $request->input('roster_id')))
            $activities->where('roster_id', $roster_id);

        return fractal(
            $activities->orderBy('updated_at', 'desc')->get(),
            new ActivityTransformer)->toArray();
    }

    function owned() {
        $request = request();
        $request->owned = true;

        return $this->index($request);
    }

    function show($id) {
        $activity = Activity::findOrFail($id);
        return fractal($activity, new ActivityTransformer)->toArray();
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
        broadcast(new \App\Events\ActivityUpdated($activity));
    }

    /**
     * Progression matrix (students / questions).
     */
    function progression($id) {
        $activity = Activity::findOrFail($id);
        $students = $activity->roster->students()->with('user')->get();
        $questions = $activity->quiz->questions()->get();
        $answers = $activity->answers()->get();

        $q = [];
        foreach ($questions as $k => $question) {
            $q[$k] = [
                'answer' => null,
                'is_correct' => false
            ];
        }

        $matrix = [];
        foreach ($students as $student) {
            $matrix[$student->id] = $q;
            foreach ($questions as $k => $question) {
                $answer = Answer::where('student_id', $student->id)
                ->where('activity_id', $activity->id)
                ->where('question_id', $questions[$k]->id)->first();
                if ($answer) {
                    $question['answer'] = $answer->answer;
                    $question['is_correct'] = $answer->is_correct;
                }
            }
        }
        return [
            'students' => $students,
            'quesions' => $questions,
            'matrix' => $matrix
        ];
    }

    function set_hidden($id) {
        $activity = Activity::findOrFail($id);

        if ($activity->status != 'finished') {
            return response([
                'message' => "Only ended activities can be hidden",
                'error' => "Bad Request"
            ], 400);
        }

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

        $activity->update(['hidden' => true]);
        broadcast(new \App\Events\ActivityUpdated($activity));
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

        $activity->update(['hidden' => false]);
        broadcast(new \App\Events\ActivityUpdated($activity));
    }

    function open($id) {
        $activity = Activity::findOrFail($id);

        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Only the owner of an activity can open an activity",
                'error' => "Unauthorized"
            ], 403);
        }

        if ($activity->hidden) {
            return response([
                'message' => "Cannot open a hidden activity",
                'error' => "Bad Request"
            ], 400);
        }

        if ($activity->status != 'idle') {
            return response([
                'message' => "Only idle activities can be opened",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->update(['opened_at' => Carbon::now()]);
        broadcast(new \App\Events\ActivityUpdated($activity));
    }

    function close($id) {
        $activity = Activity::findOrFail($id);

        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Only the owner of an activity can close an activity",
                'error' => "Unauthorized"
            ], 403);
        }

        if ($activity->status != 'opened') {
            return response([
                'message' => "Only opened activities can be closed",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->update(['opened_at' => null]);
        $activity->save();

        broadcast(new \App\Events\ActivityUpdated($activity));
    }

    function delete($id) {
        $activity = Activity::findOrFail($id);

        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Only the owner can delete this activity",
                'error' => "Unauthorized"
            ], 403);
        }

        if ($activity->status != 'idle') {
            return response([
                'message' => "Cannot delete a completed activity",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->delete();

        broadcast(new \App\Events\ActivityUpdated(null));
    }

    function create(Request $request) {
        Log::debug('Create Activity Request');
        $validator = Validator::make($request->all(), [
            'duration' => 'required|integer|min:10',
            'quiz_id' => 'required|exists:quizzes,id',
            'roster_id' => 'required|exists:rosters,id',
            'shuffle_questions' => 'boolean',
            'shuffle_propositions' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Missing or invalid fields',
                'details' => $validator->messages(),
                'error' => 'Bad Request',
            ], 400);
        }

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

        $quiz = Quiz::findOrFail($request->quiz_id);

        $activity = Activity::create([
            'user_id' => Auth::id(),
            'roster_id' => $request->roster_id,
            'quiz_id' => $quiz->id,
            'duration' => $request->input('duration', 600),
            'shuffle_questions' => $request->input('shuffle_questions', false),
            'shuffle_propositions' => $request->input('shuffle_propositions', false),
            'seed' => $request->input('seed', random_int(0, 4294967295))
        ]);

        broadcast(new \App\Events\ActivityUpdated($activity));

        return response([
            'message' => 'New activity created',
            'activity' => $activity,
        ], 200);
    }

    /**
     * Validate an answer
     */
    protected function validate_answer($given, $wanted) {
        return true;
    }



    /**
     * Questions
     */
    public function questions($activity_id) {
        $activity = Activity::findOrFail($activity_id);
        return fractal(
            $activity->questions(),
            new QuestionTransformer($activity))->toArray();
    }

    /**
     * Question
     */
    public function question($activity_id, $question_number) {
        // Todo...
    }

    /**
     * Get the results if the activity is finished
     */
    public function results($activity_id) {
        $activity = Activity::findOrFail($activity_id);

        if ($activity->status != 'finished' || $activity->hidden) {
            return response([
                'message' => "No accessible results for this activity",
                'error' => "Bad Request"
            ], 400);
        }

        return $activity->ownedAnswers()->get();
    }
}
