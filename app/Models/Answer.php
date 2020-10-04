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
}
