<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    function students() {
        return $this->belongsToMany('App\Models\Student');
    }
}
