<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Transformer\QuizTransformer;
use App\Models\Quiz;
use App\Models\Question;
use Auth;
use Log;

class QuizController extends Controller
{
    function index(Request $request) {
        if ($request->owned)
            $quiz = Quiz::where('user_id', Auth::id())->get();
        else
            $quiz = Quiz::all();

        return fractal($quiz, new QuizTransformer())->toArray();
    }

    function show($id) {
        $quiz = Quiz::withCount('questions')->find($id);
        $quiz['questions'] = url("/api/quizzes/{$quiz['id']}/questions");
        $quiz['activities'] = url("/api/quizzes/{$quiz['id']}/activities");

        $quiz['@create_activity'] = url("/api/activities/create");
        return $quiz;
    }

    function question($id, $question_id) {
        return Quiz::find($id)->questions()->findOrFail($question_id);
    }

    function questions($id) {
        $questions = Quiz::find($id)->questions()->get()->each(function ($item, $key) {
        });
        return [
            'count' => count($questions),
            'quiz_id' => $id,
            'questions' => $questions
        ];
    }

    function activities($id) {
        $activities = Quiz::find($id)->activities()->get()->each(function ($item, $key) {
        });
        return [
            'count' => count($activities),
            'quiz_id' => $id,
            'activities' => $activities
        ];
    }

    function create(Request $request) {
        if( !Auth::user()->isTeacher() ){
            return response([
                'message' => "Only the teacher can create a quiz",
                'error' => "Bad Request"
            ], 400);
        };

        Log::debug('Create quiz');
        $data = $request->all();
        $q = new Quiz();
        $q->fill($data);
        $q->user_id = Auth::id();

        $q->save();

        return response([
            'message' => 'Quiz created'
        ], 200);
    }

    function deleteQuestion(Request $request) {
        Log::debug('Delete question to quiz');

        $quiz = Quiz::findOrFail($request->quiz_id);
        if ($quiz->user_id != Auth::id()) {
            return response([
                'message' => "Only the quiz's teacher can delete a question",
                'error' => "Bad Request"
            ], 400);
        }

        $question = Question::findOrFail($request->question_id);

        $quiz->questions()->detach($question->id);

        $questions = Quiz::findOrFail($request->quiz_id)->questions;
        $quiz = fractal($quiz, new QuizTransformer())->toArray();
        return response([
            'message' => 'Question deleted',
            'quiz' => $quiz,
            'questions' => $questions
        ], 200);
    }

    function addQuestion(Request $request) {
        Log::debug('Add question to quiz');

        $quiz = Quiz::findOrFail($request->quiz_id);
        if ($quiz->user_id != Auth::id()) {
            return response([
                'message' => "Only the quiz's teacher can add a question",
                'error' => "Bad Request"
            ], 400);
        }

        $question = Question::findOrFail($request->question_id);

        $quiz->questions()->attach($question->id);

        $questions = Quiz::findOrFail($request->quiz_id)->questions;
        $quiz = fractal($quiz, new QuizTransformer())->toArray();
        return response([
            'message' => 'Question added',
            'quiz' => $quiz,
            'questions' => $questions
        ], 200);
    }
}
