<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');});
Route::get('/quiz/{any}', function () {return view('vue');})->where('any', '.*');

Auth::routes(['register' => false]);

Route::get('/shibboleth-data', function () {});
