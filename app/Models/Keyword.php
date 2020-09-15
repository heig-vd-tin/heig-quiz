<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Models\Question');
    }
}
