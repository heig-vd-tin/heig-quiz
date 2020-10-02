<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Activity;

use App\Transformer\QuestionTransformer;

class QuestionController extends Controller
{
    protected $user;

    function __construct()
    {
        $user = Auth::user();
    }

    function index(Request $request)
    {
        if (!$this->user->isTeacher()) abort(403);

        $questions = Question::query();

        return fractal($questions->get(), new QuestionTransformer())->toArray();
    }

}
