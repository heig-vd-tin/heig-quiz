<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Question;

class Quiz extends Model
{
    function questions() {
        return $this->hasMany(Question::class);
    }
}
