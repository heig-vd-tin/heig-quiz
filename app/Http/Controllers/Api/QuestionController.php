<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;

class QuestionController extends Controller
{
    function index($id) {
        return Question::all();
    }
}
