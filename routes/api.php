<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {

    Route::get('keywords', 'KeywordController@index');
    Route::get('keywords/{category}', 'KeywordController@with_category');


    Route::get('question/{id}', 'QuizController@index')->name('question');
    Route::get('quiz/{id}', 'QuizController@getQuiz')->name('quiz');

    Route::get('classroom/{id}', 'ClassroomController@getClass');

    Route::get('course/{id}', 'ClassroomController@getCourse');

    Route::get('activity/answer/{id}', 'ActivityController@getActivityAnswer');
    Route::get('activity/{activity_id}/{num}', 'ActivityController@getQuestion');
    Route::get('activity', 'ActivityController@getMyActivities');
    Route::get('activity/{id}', 'ActivityController@getActivity');
});
