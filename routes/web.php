<?php
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/quiz/{any}', function () {return view('vue');})->where('any', '.*');

Auth::routes(['register' => false]);

Route::get('/shibboleth-data', function () {});

if (config('app.debug')) {
    Route::get('/debug/login/{id}', function ($id) {
        Auth::loginUsingId($id);
    });

    Route::get('/sandbox', function () {

        $validator = new JsonSchema\Validator;
        $data = (object)[
            'duration' => 144,
            'type' => 'short-answer',
            'validation' => '',
            'lines' => 'salut'
        ];
        $validator->validate($data, (object)['$ref' => 'file://' . base_path('resources/schemas/question.schema.json')]);
        return [
            'is_valid' => $validator->isValid(),
            'errors' => $validator->getErrors()
        ];
    });
}
