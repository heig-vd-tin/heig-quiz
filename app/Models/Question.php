<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use JsonSchema\Validator;
use Log;

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

    /**
     * Check if the answered value is valid
     */
    function validate($value) {
        switch($this->type) {
            case 'short-answer':
                return $this->validateShortAnswer($value);
            case 'multiple-choice':
                return $this->validateMultipleChoice($value);
            case 'code':
                return $this->validateCode($value);
            case 'fill-in-the-gaps':
                return $this->validateFillInTheGaps($value);
        }
        return false;
    }

    protected function validateShortAnswer($value) {
        if (Arr::has($this, 'validation.trim'))
            $value = trim($value);

        if (Arr::has($this, 'validation.equals') && $value == Arr::get($this, 'validation.equals'))
            return true;

        if (Arr::has($this, 'validation.pattern')) {
            if (preg_match(Arr::get($this, 'validation.pattern'), $value)) {
                return true;
            }
        }

        return false;
    }

    protected function validateMultipleChoice($value) {
        return false;
    }

    protected function validateCode($value) {
        return false;
    }

    protected function validateFillInTheGaps($value) {
        return false;
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
