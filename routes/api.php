<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {
    // Public
    Route::get('keywords', 'KeywordController@index');
    Route::get('keywords/{category}', 'KeywordController@with_category');

    // Teacher
    Route::group(['middleware' => 'role:teacher'], function() {
        Route::get('students', 'StudentController@index');
        Route::get('courses', 'CourseController@index');
        Route::get('classrooms/{id}', 'ClassroomController@getClass');
    });

    // Student
    Route::group(['middleware' => 'role:student'], function() {

    });

    Route::get('quizzes', 'QuizController@index');

    Route::get('question/{id}', 'QuizController@index')->name('question');
    Route::get('quiz/{id}', 'QuizController@getQuiz')->name('quiz');

    Route::get('activities/answer/{id}', 'ActivityController@getActivityAnswer');
    Route::get('activities/{activity_id}/{num}', 'ActivityController@getQuestion');
    Route::get('activities', 'ActivityController@getMyActivities');
    Route::get('activities/{id}', 'ActivityController@getActivity');
});
