<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {

    Route::get('keywords', 'KeywordController@index');
    Route::get('keywords/{category}', 'KeywordController@with_category');

    Route::get('students', 'StudentController@index')->middleware('role:teacher');

    Route::get('courses', 'CourseController@index');


    Route::get('question/{id}', 'QuizController@index')->name('question');
    Route::get('quiz/{id}', 'QuizController@getQuiz')->name('quiz');

    Route::get('classroom/{id}', 'ClassroomController@getClass');


    Route::get('activity/answer/{id}', 'ActivityController@getActivityAnswer');
    Route::get('activity/{activity_id}/{num}', 'ActivityController@getQuestion');
    Route::get('activity', 'ActivityController@getMyActivities');
    Route::get('activity/{id}', 'ActivityController@getActivity');
});
