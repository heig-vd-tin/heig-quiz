<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Activity;

use Auth;

use App\Transformer\QuestionTransformer;

class QuestionController extends Controller
{
    function index(Request $request)
    {
        if (!$request->user()->isTeacher()) abort(403);

        $questions = Question::with('keywords');

        return fractal($questions->get(), new QuestionTransformer())->toArray();
    }

}
