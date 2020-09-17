<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\Answer;

class Activity extends Model
{
    protected $appends = array('number');

    public function getNumberAttribute()
    {
        return $this->quiz->question->count();  
    }

    function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    function teacher() {
        return $this->belongsTo('App\User');
    }

    function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    function answer() {
        return $this->hasMany(Answer::class);
    }
}
