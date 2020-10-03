<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}
