<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Activity;

class Answer extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'answer' => 'array', // JSON
        'is_correct' => 'boolean'
    ];

    function activity() {
        return $this->belongsTo(Activity::class);
    }

    function student() {
        return $this->belongsTo(Student::class);
    }


    protected function checkShortAnswer() {
        if ($this->options->pattern)
            if (!preg_match($this->options->pattern, $this->answer))
                return false;
        return true;
    }

    protected function checkMultipleChoice() {

    }

    protected function checkCode() {

    }

    protected function checkFillInTheGaps() {

    }

    /**
     * Check and validate an answer
     */
    function check() {
        $is_valid = false;
        switch($this->type) {
            case 'short-answer':
                $is_valid = $this->checkShortAnswer();
            break;
            case 'multiple-choice':
                $is_valid = $this->checkMultipleChoice();
            break;
            case 'code':
                $is_valid = $this->checkCode();
            break;
            case 'fill-in-the-gaps':
                $is_valid = $this->checkFillInTheGaps();
            break;
        }
        $this->update(['is_correct' => $is_valid]);
    }
}
