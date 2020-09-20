<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Activity extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'elapsed',   // Number of seconds elapsed since the beginning
        'started',   // The activity has started and isn't yet finished
        'completed'  // The activity is finished
    ];

    function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    function teacher() {
        return $this->belongsTo(User::class);
    }

    function roster() {
        return $this->belongsTo(Roster::class);
    }

    function answers() {
        return $this->hasMany(Answer::class);
    }

    function getElapsedAttribute() {
        if (!$this->started_at)
            return 0;

        $elapsed = Carbon::now()->diffInSeconds(Carbon::create($this->started_at));
        if ($elapsed < 0) {
            Log::critical("Negative time difference detected in activity {$this->id}");
            return $elapsed;
        }

        return min($elapsed, $this->duration);
    }

    function getStartedAttribute() {
        return $this->elapsed > 0 && $this->elapsed < $this->duration;
    }

    function getCompletedAttribute() {
        return $this->elapsed >= $this->duration;
    }
}
