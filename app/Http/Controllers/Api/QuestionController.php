<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Activity;
use App\Models\Keyword;

use Auth;
use Log;

use App\Transformer\QuestionTransformer;

use function PHPUnit\Framework\isEmpty;

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
            $q = Question::where('user_id', $i)->get();
        }
        else{
            $q = Question::whereHas('keywords', function ($query) use ($keyword) {
                return $query->where('name', 'like', $keyword)->where('user_id', $i);
            })->get();
        }

        return $q;
    }

    function testQuestion(Request $request){
        $i = Auth::id();

        if (!$request->user()->isTeacher()){
            return response([
                'message' => "Only teacher can test questions",
                'error' => "Bad Request"
            ], 400);
        }

        $data = $request->all();
        $q = new Question($data);
        $q['answer_ok'] = $q->validate($data['answer']);
        return $q;
    }

    function create(Request $request) {
        Log::debug('Create question');
        $request->user_id = 1;
        
        $data = $request->all();
        $data['user_id'] = Auth::id();

        if(array_key_exists('id', $data) && $data['id'])
        {
            $q = Question::findOrFail($data['id']);
            $q->fill($data);
        }
        else
        {
            $q = new Question();
            $q->fill($data);
        }

        $q->save();

        // Delete keywords
        foreach($q->keywords as $qk)
        {
            $exist = false;
            if(array_key_exists('keywords', $data) && $data['keywords'])
            {
                foreach($data['keywords'] as $dk)
                {
                    if( $dk['id'] == $qk['id'])
                    {
                        $exist = true;
                        break;
                    }
                }
            }
            if(!$exist)
            {
                $q->keywords()->detach($qk['id']);
            }
        }

        $tab = [];
        if(array_key_exists('keywords', $data) && $data['keywords'])
        {
            //$ids = array_column($data['keywords'], 'id');
            foreach($data['keywords'] as $k)
            {
                $nbr = Question::where('id', $q['id'])
                        ->whereHas('keywords', function($query) use ($k) {
		                    $query->where('keywords.id', $k['id']);
	                    })->count();
                        
                array_push($tab, ["id" => $k['id'], "nbr" => $nbr] );

                if($nbr == 0){
                    $q->keywords()->attach($k['id']);
                }
            }
        }

        return response([
            'message' => 'Question created',
            'roster' => $q
        ], 200);
    }

    function delete(Request $request) {
        return Question::where('id', $request->id)->where('user_id', Auth::id())->delete();
    }
}
