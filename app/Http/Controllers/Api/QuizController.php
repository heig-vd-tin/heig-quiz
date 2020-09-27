<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Transformer\QuizTransformer;
use App\Models\Quiz;

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
}
