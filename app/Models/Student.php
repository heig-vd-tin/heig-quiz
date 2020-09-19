<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $hidden = ['pivot'];

    function rosters() {
        return $this->belongsToMany(Roster::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
