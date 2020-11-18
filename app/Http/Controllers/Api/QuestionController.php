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

    function getQuestions(Request $request, $keyword)
    {
        $i = Auth::id();

        if (!$request->user()->isTeacher()){
            return response([
                'message' => "Only teacher can get questions",
                'error' => "Bad Request"
            ], 400);
        }

        if( $keyword == "all"){
            $q = Question::with('keywords')->get();
        }
        else{
            $q = Question::whereHas('keywords', function ($query) use ($keyword) {
                return $query->where('name', 'like', $keyword);
            })->get();
        }

        return $q;
    }
}
