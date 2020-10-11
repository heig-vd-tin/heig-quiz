<?php
use Illuminate\Support\Facades\Route;

Route::get('/shibboleth-data', function () {});

if (config('app.debug')) {
    Route::get('/debug/login/{id}', function ($id) {
        Auth::loginUsingId($id);
        return redirect('/');
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

Route::get('/{any}', function () {
    return view('app');}
)->where('any', '.*');

