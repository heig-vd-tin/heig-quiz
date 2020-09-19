<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

function is_teacher() {
    return Auth::user()->hasRole('teacher');
}

Route::namespace('Api')->group(function () {
    // Public
    Route::get('/', function() {
        return [
            'keywords' => url('/api/keywords'),
            'students' => is_teacher() ? url('/api/students') : null,
            'courses' => is_teacher() ? url('/api/courses') : null,
            'users' => is_teacher() ? url('/api/users') : null
        ];
    });

    Route::get('keywords', 'KeywordController@index');
    Route::get('keywords/{category}', 'KeywordController@with_category');

    // Teacher
    Route::group(['middleware' => 'role:teacher'], function() {
        Route::get('students', 'StudentController@index');

        Route::get('users', 'UserController@index');
        Route::get('users/{id}', 'UserController@show');
        Route::get('users/{id}/activities', 'UserController@activities');
        Route::get('users/{id}/classrooms', 'ClassroomController@teacher_classrooms');

        Route::get('courses', 'CourseController@index');

        Route::get('classrooms', 'ClassroomController@index');
        Route::get('classrooms/{id}', 'ClassroomController@show');
        Route::get('classrooms/{id}/students', 'ClassroomController@students');
        Route::get('classrooms/{id}/course', 'ClassroomController@course');
        Route::get('classrooms/{id}/activities', 'ClassroomController@activities');
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
