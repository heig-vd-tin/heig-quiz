<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $withCount = [
        'students'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    function students() {
        return $this->belongsToMany('App\Models\Student');
    }

    function activities() {
        return $this->hasMany(Activity::class);
    }
}
