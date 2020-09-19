<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Keyword;
use App\Models\Quiz;

class QuizController extends Controller
{
    function index($id) {
        return Question::with('keyword')->find(1);
    }

    function getQuiz($id) {
        return Quiz::with('question')->find(1);
    }

    function getKeywords() {
        return Keyword::orderBy('name')->get();
    }
}
