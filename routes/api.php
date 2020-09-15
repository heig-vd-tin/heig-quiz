<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->namespace('Api')
    ->group(
        function () {
            Route::get('question/{id}', 'QuizController@index')->name('question');
            Route::get('keyword', 'QuizController@getKeywords')->name('keyword');
            Route::get('quiz/{id}', 'QuizController@getQuiz')->name('quiz');
        }
    );

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Route::middleware('auth:api')->prefix('quiz')->group(
//     ['middleware' => App\Http\Middleware\TeacherMiddleware::class], function () {

//     Route::get('activities', 'Api\\ActivityController@index');
//     Route::get('activities/{id}/stop', 'Api\\ActivityController@stop');

//     Route::get('{id}', 'Api\\QuizController@show');
// });

/*
{
    "api/activities": {
        "count": 1,
        "activities": [
            {
                "roster": 2,
                "quiz": 21,
                "started-at": 8219321784,
                "duration": 10,
                "status": "in-progress"
            }
        ]
    },
    "api/quiz": {
        "id": 21,
        "shuffle-questions": true,
        "shuffle-propositions": true,
        "seed": -3831274212,
        "name": "Name of the quiz",
        "difficulty": 4,
        "keywords": [
            "struct"
        ],
        "current_question": 2
    },
    "api/quiz/1": { // Question
        "title": "Question 1",
        "content": "A question with multiple choices?",
        "choices": [
            {
                "proposition": "A",
                "text": "This is answer A"
            },
            {
                "proposition": "B",
                "text": "This is answer B"
            },
            {
                "proposition": "C",
                "text": "This is answer C"
            },
            {
                "proposition": "D",
                "text": "This is answer D"
            },
        ]
    },
    "api/quiz/correction": { // Uniquement après que le quiz ait été passé
        "title": "Question 1",
        "questions": [{
            "content": "A question with multiple choices?",
            "choices": [
                {
                    "proposition": "A",
                    "text": "This is answer A",
                    "percent": 12.1
                },
                {
                    "proposition": "B",
                    "text": "This is answer B",
                    "percent": 39.1
                },
                {
                    "proposition": "C",
                    "text": "This is answer C",
                    "percent": 0.5
                },
                {
                    "proposition": "D",
                    "text": "This is answer D",
                    "percent": 51.7
                }
            ],
            "answer": 0,
            "rationale": "Text of rationale"
        }]
    },
    "api/rosters": [
        {
            "id": 1,
            "name": "Info1-TIN-D",
            "course": "Info1",
            "department": "TIN",
            "class": "D",
            "years": [2020, 2021],
            "semester": 0,
        }
    ],
    "api/rosters/1": [{
        "count": 1,
        "students": [
            {
                "id": 102,
                "email": "neila.chaabane@heig-vd.ch",
                "gaps-name": "Chaabane Neila",
                "orientation": "EAI",
                "type": "full-time",
                "quizzes-taken": 3,
                "last-connection": "2020-03-02T12:21:34"
            }
        ]
    }],
    "api/students/1": [{
        "id": 102,
        "email": "neila.chaabane@heig-vd.ch",
        "gaps-name": "Chaabane Neila",
        "orientation": "EAI",
        "type": "full-time"
    }]
}
*/
