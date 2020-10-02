<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JsonSchema\Validator;

class Question extends Model
{
    use HasFactory;

    protected $casts = [
        'validation' => 'array', // JSON
        'options' => 'array' // JSON
    ];

    //protected $hidden = ['pivot'];

    function keywords() {
        return $this->belongsToMany(Keyword::class);
    }

    function quiz() {
        return $this->belongsToMany(Quiz::class);
    }

    function answers() {
        return $this->hasMany(Answer::class);
    }

    // TODO: Make validation occur with doc/schemas/validation
    // function setValidationAttribute() {

    // }

    // function setOptionsAttribute($data) {
    //     $validator = new Validator;
    //     $validator->validate($data, (object)['$ref' => 'file://' . realpath('doc/schemas/question.schema.json')]);

    //     if (!$validator->isValid()) {
    //         dd($validator->getErrors());
    //     }

    //     return $data;
    // }
}
