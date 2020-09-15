<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\Answer;
use Auth;

class ActivityController extends Controller
{
    function index() {
        return Activity::where('class.teacher_id', User::id());
    }

    function getActivity() {
        return Activity::with(['quiz','classroom'])->find(1);
    }

    function startActivity($id) {
        $act = Activity::with(['answer.student'])->find($id);
        if( $act->teacher->id == Auth::user()->id ){
            //Todo(tmz) Start activity
        }
    }

    function getActivityAnswer($id) {
        //if( Auth::user() ) //Todo(tmz) Check if teacher
        return Activity::with(['answer.student'])->find($id);
    }

    function getQuestion($activity_id, $num) {
        $user_id = Auth::user()->id;
        $user_id = 2; //Todo(tmz) Debug
        $student = Student::where('user_id', '=', $user_id)->first();

        $act = Activity::find($activity_id);
        if(!$act->classroom->students->contains('id', $student->id)){
            return;
        }

        if($student && $act->state != 'in_progress'){
            return;
        }

        $nbr_question = $act->quiz->question->count();
        $question_id = $num < $nbr_question ? $num + 1 : 1;

        $ans = Answer::where('activity_id', $activity_id)->
                    where('question_id', $question_id)->
                    where('student_id', $student->id)->
                    first();

        return [Question::find($question_id), $ans->answer];
    }

    function getMyActivities() {
        $user_id = Auth::user()->id;
        $user_id = 1; //Todo(tmz) Debug
        $student = Student::where('user_id', '=', $user_id)->first();
        $act = [];
        if($student) {
            $classes = $student->classrooms;
        }
        else {
            $classes = Classroom::all();
        }

        foreach($classes as $cl) {
            array_push($act, Activity::where('classroom_id', '=', $cl->id)->get()->all()); 
        }
        return $act;
    }
}
