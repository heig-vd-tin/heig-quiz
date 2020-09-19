<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Keyword;
use App\Models\Quiz;

class QuizController extends Controller
{
    function index() {
        $quiz = Quiz::withCount('questions')->get()->each(function ($item, $key) {
            $item['questions'] = url("/api/quiz/{$item['id']}/questions");
            $item['activities'] = url("/api/quiz/{$item['id']}/activities");
        });
        return [
            'count' => count($quiz),
            'quizzes' => $quiz
        ];
    }

    function show($id) {
        $quiz = Quiz::withCount('questions')->find($id);
        $quiz['questions'] = url("/api/quizzes/{$quiz['id']}/questions");
        $quiz['activities'] = url("/api/quizzes/{$quiz['id']}/activities");
        return $quiz;
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

    function getQuiz($id) {
        return Quiz::with('question')->find(1);
    }

    function getKeywords() {
        return Keyword::orderBy('name')->get();
    }
}
