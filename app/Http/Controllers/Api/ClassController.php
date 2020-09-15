<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Class

class ClassController extends Controller
{
    function index($id) {
        return Question::with('keyword')->find(1);
    }

    function getQuiz($id) {
        return Quiz::with('question')->find(1);
    }

    function getKeywords() {
        return Keyword::all();
    }
}
