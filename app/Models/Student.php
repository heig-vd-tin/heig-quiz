<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    function rosters() {
        return $this->belongsToMany(Roster::class);
    }

    function activities() {
        $activities = Activity::query();
        foreach ($this->rosters as $roster) {
            $activities->orWhere('roster_id', $roster->id);
        }
        return $activities;
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
