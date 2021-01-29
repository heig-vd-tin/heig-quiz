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

    protected $guarded = ['id', 'created_at', 'updated_at'];

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
        $target = array_unique($this->validation);
        sort($target);
        $answer = array_unique($value);
        sort($value);

        return $value == $target;
    }

    protected function validateFillInTheGaps($value) {
        return $value == $this->validation;
    }

    protected function validateCode($value) {
        //$output = shell_exec('RET=`docker run hello-world`;echo $RET');
        $url = 'https://rextester.com/rundotnet/api';

        $data = array(
            'LanguageChoice' => '6',
            'Program' => $value,
            'Input' => '',
			'CompilerArgs' => '-Wall -o a.out source_file.c'
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $this['test_info'] = $result;
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
