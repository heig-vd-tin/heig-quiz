<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Proposition;

class Question extends Model
{
    use HasFactory;

    protected $casts = [
        'answer' => 'array', // JSON
        'options' => 'array' // JSON
    ];
    protected $hidden = ['pivot'];

    function keywords() {
        return $this->belongsToMany(Keyword::class);
    }

    function quiz() {
        return $this->belongsToMany(Quiz::class);
    }

    function answers() {
        return $this->hasMany(Answer::class);
    }
}
