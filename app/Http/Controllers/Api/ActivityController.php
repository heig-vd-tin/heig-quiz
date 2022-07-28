<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

use App\Models\Activity;
use App\Models\Roster;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\Question;
use Validator;
use Arr;
use Auth;
use Log;
use File;

use App\Transformer\ActivityTransformer;
use App\Transformer\QuestionTransformer;
use SebastianBergmann\Environment\Console;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use stdClass;

class ActivityController extends Controller
{
  /**
   * Get all activities for teachers and participating in activites for students.
   */
  function index(Request $request)
  {
    $activities = Activity::query();

    if ($request->user()->isStudent())
      $activities = $request->user()->student->activities()->where('hidden', false);

    if ($request->user()->isTeacher() && $request->owned)
      $activities = $request->user()->activities()->where('id', Auth::id())->get();

    if (($roster_id = $request->input('roster_id')))
      $activities->where('roster_id', $roster_id);

    return fractal(
      $activities->orderBy('updated_at', 'desc')->get(),
      new ActivityTransformer
    )->toArray();
  }

  function owned()
  {
    $request = request();
    $request->owned = true;

    return $this->index($request);
  }

  function show($id)
  {
    $activity = Activity::findOrFail($id);
    return fractal($activity, new ActivityTransformer)->toArray();
  }

  function roster($id)
  {
    $roster = Activity::findOrFail($id)->roster()->get()->each(function ($item, $key) {
      $item['course'] = url("/api/courses/{$item['course_id']}");
      $item['teacher'] = url("/api/users/{$item['teacher_id']}");
    });
    return $roster;
  }

  function studentList($id)
  {
    $activity = Activity::findOrFail($id);
    $students = $activity->roster->students()->with('user')->get();
    return $students;
  }

  function addTime(Request $data){

    $activity = Activity::findOrFail($data->id);
    $activity->duration += $data->time;
    return $activity->save();
  }

  function studentAnswers($activity_id, $student_id)
  {
    $answers = Answer::where([['activity_id', $activity_id], ['student_id', $student_id]])->get();
    return $answers;
  }

  function updateAnswer(Request $request)
  {
    $answer = null;
    
    if($request->id != -1) {
      $answer = Answer::find($request->id);
      if ($answer !== null) {
        $answer->points = $request->points;
        $answer->is_correct = $request->is_correct;
        $answer->new_validation = $request->new_validation;
      }
    } else {
      
      $answer = new Answer();
      $answer->student_id = $request->student_id;
      $answer->activity_id = $request->activity_id;
      $answer->question_id = $request->question_id;
      $answer->points = $request->points;
      $answer->new_validation = $request->new_validation;
      
    }
    $answer->save();
    return $answer;
  }

  function compilation(Request $request)
  {
    

    $question = Question::find($request->id);
    $language = $question->options['language'];

    $url = 'http://localhost:8080/compiler/';
    $code = $request->value;
    $fileName = '';

    switch ($language) {
      case 'C':
        $url = $url . 'c';
        $fileName = 'sourceCode.c';
        break;
      case 'CPP':
        $url = $url . 'cpp';
        $fileName = 'sourceCode.cpp';
        break;
      case 'JAVA':
        $url = $url . 'java';
        $fileName = 'sourceCode.java';
        break;
      case 'PYTHON':
        $url = $url . 'python';
        $fileName = 'sourceCode.py';
        break;
    }

    $response = Http::attach('inputFile', '1', 'input.txt')
                    ->attach('outputFile', $question->validation, 'output.txt')
                    ->attach('sourceCode', $code, $fileName)
                    ->post($url, [
                      'memoryLimit' => 500,
                      'timeLimit' => 30
                    ]);
    
    return $response;
  }

  function rosterActivities($roster)
  {
    $activities = Roster::with('activities')->findOrFail($roster)->activities->each(function ($item, $key) {
      $item['quiz'] = url("/api/quizzes/{$item['quiz_id']}");
      $item['roster'] = url("/api/rosters/{$item['roster_id']}");
      $item['questions'] = url("/api/quizzes/{$item['quiz_id']}/questions");
    });
    return [
      'count' => count($activities),
      'roster' => $roster,
      'activities' => $activities
    ];
  }

  function start($id)
  {
    $activity = Activity::findOrFail($id);
    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner of an activity start it",
        'error' => "Unauthorized"
      ], 403);
    }

    if ($activity->hidden) {
      return response([
        'message' => "Cannot start an hidden activity",
        'error' => "Bad Request"
      ], 400);
    }

    if ($activity->completed) {
      return response([
        'message' => "Cannot restart an activity",
        'error' => "Bad Request"
      ], 400);
    }

    if ($activity->started) {
      return response([
        'message' => "Activity activity started",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->started_at = Carbon::now();
    $activity->save();
  }
  

  /**
   * Progression matrix (students / questions).
   */
  function progression($id)
  {
    $activity = Activity::findOrFail($id);
    $students = $activity->roster->students()->with('user')->get();
    $questions = $activity->quiz->questions()->get();
    $answers = $activity->answers()->get();

    $q = [];
    foreach ($questions as $k => $question) {
      $q[$k] = [
        'answer' => null,
        'is_correct' => false,
        'need_help' => false
      ];
    }

    $matrix = [];
    foreach ($students as $student) {
      $matrix[$student->id] = $q;
      foreach ($questions as $k => $question) {
        $answer = Answer::where('student_id', $student->id)
          ->where('activity_id', $activity->id)
          ->where('question_id', $questions[$k]->id)->first();
        if ($answer) {
          $matrix[$student->id][$k]['answer'] = $answer->answer;
          $matrix[$student->id][$k]['is_correct'] = $answer->is_correct;
          $matrix[$student->id][$k]['need_help'] = $answer->need_help;
        }
      }
    }
    return $matrix;
    return [
      'students' => $students,
      'quesions' => $questions,
      'matrix' => $matrix
    ];
  }

  function set_hidden($id)
  {
    $activity = Activity::findOrFail($id);

    if ($activity->status != 'finished') {
      return response([
        'message' => "Only ended activities can be hidden",
        'error' => "Bad Request"
      ], 400);
    }

    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner of an activity can change the visibility",
        'error' => "Unauthorized"
      ], 403);
    }

    if ($activity->hidden) {
      return response([
        'message' => "Activity already hidden",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->update(['hidden' => true]);
  }

  function set_visible($id)
  {
    $activity = Activity::findOrFail($id);

    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner of an activity can change the visibility",
        'error' => "Unauthorized"
      ], 403);
    }

    if (!$activity->hidden) {
      return response([
        'message' => "Activity already visible",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->update(['hidden' => false]);
  }

  function open($id)
  {
    $activity = Activity::findOrFail($id);

    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner of an activity can open an activity",
        'error' => "Unauthorized"
      ], 403);
    }

    if ($activity->hidden) {
      return response([
        'message' => "Cannot open a hidden activity",
        'error' => "Bad Request"
      ], 400);
    }

    if ($activity->status != 'idle') {
      return response([
        'message' => "Only idle activities can be opened",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->update(['opened_at' => Carbon::now()]);
  }

  function finish($id)
  {
    $activity = Activity::findOrFail($id);

    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner of an activity can finish an activity",
        'error' => "Unauthorized"
      ], 403);
    }

    if ($activity->hidden) {
      return response([
        'message' => "Cannot finish a hidden activity",
        'error' => "Bad Request"
      ], 400);
    }

    if ($activity->status != 'running') {
      return response([
        'message' => "Only running activities can be finished",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->update(['ended_at' => Carbon::now()]);
  }

  function close($id)
  {
    $activity = Activity::findOrFail($id);

    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner of an activity can close an activity",
        'error' => "Unauthorized"
      ], 403);
    }

    if ($activity->status != 'opened') {
      return response([
        'message' => "Only opened activities can be closed",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->update(['opened_at' => null]);
    $activity->save();
  }

  function delete($id)
  {
    $activity = Activity::findOrFail($id);

    if ($activity->user_id != Auth::id()) {
      return response([
        'message' => "Only the owner can delete this activity",
        'error' => "Unauthorized"
      ], 403);
    }

    if ($activity->status != 'idle') {
      return response([
        'message' => "Cannot delete a completed activity",
        'error' => "Bad Request"
      ], 400);
    }

    $activity->delete();
  }

  function create(Request $request)
  {
    Log::debug('Create Activity Request');
    $validator = Validator::make($request->all(), [
      'duration' => 'required|integer|min:10',
      'quiz_id' => 'required|exists:quizzes,id',
      'roster_id' => 'required|exists:rosters,id',
      'shuffle_questions' => 'boolean',
      'shuffle_propositions' => 'boolean',
    ]);

    if ($validator->fails()) {
      return response([
        'message' => 'Missing or invalid fields',
        'details' => $validator->messages(),
        'error' => 'Bad Request',
      ], 400);
    }

    if (Roster::findOrFail($request->input('roster_id'))->teacher_id != Auth::id()) {
      return response([
        'message' => "Only the roster's teacher can create an activity",
        'error' => "Bad Request"
      ], 400);
    }

    if (Roster::findOrFail($request->input('roster_id'))->teacher_id != Auth::id()) {
      return response([
        'message' => "Only the roster's teacher can create an activity",
        'error' => "Bad Request"
      ], 400);
    }

    $quiz = Quiz::findOrFail($request->quiz_id);

    $activity = Activity::create([
      'user_id' => Auth::id(),
      'roster_id' => $request->roster_id,
      'quiz_id' => $quiz->id,
      'duration' => $request->input('duration', 600),
      'shuffle_questions' => $request->input('shuffle_questions', false),
      'shuffle_propositions' => $request->input('shuffle_propositions', false),
      'seed' => $request->input('seed', random_int(0, 4294967295)),
      'hidden' => false
    ]);

    return response([
      'message' => 'New activity created',
      'activity' => $activity,
    ], 200);
  }

  /**
   * Questions
   */
  public function questions($activity_id)
  {
    $activity = Activity::findOrFail($activity_id);
    return fractal(
      $activity->questions(),
      new QuestionTransformer($activity)
    )->toArray();
  }

  /**
   * Question (get or post question)
   */
  public function question($activity_id, $question_number, Request $request)
  {
    $activity = Activity::findOrFail($activity_id);
    $question = $activity->questions()[$question_number - 1];

    // Submit an answer?
    if ($request->isMethod('post') && $activity->status == 'running') {
      $answered = $request->answer;
      $need_help = $request->need_help;
      
      $is_correct = false;
      $is_correct = $question->validate($answered);

      $points = 0;
      
      if ($is_correct) {
        $points = $question->points;
      }
      Answer::updateOrCreate(
        [
          'activity_id' => $activity_id,
          'student_id' => $request->user()->student->id,
          'question_id' => $question->id,
        ],
        [
          'answer' => $answered,
          'is_correct' => $is_correct,
          'need_help' => $need_help,
          'points' => $points
        ]
      );
    }

    return fractal(
      $question,
      new QuestionTransformer($activity)
    )->toArray();
  }

  /**
   * Get the results if the activity is finished
   */
  public function results($activity_id)
  {
    $activity = Activity::findOrFail($activity_id);

    if ($activity->status != 'finished' || $activity->hidden) {
      return response([
        'message' => "No accessible results for this activity",
        'error' => "Bad Request"
      ], 400);
    }

    return $activity->ownedAnswers()->get();
  }
}
