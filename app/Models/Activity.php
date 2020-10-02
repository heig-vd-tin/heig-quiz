<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use Auth;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'status',    // Current activity status
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

    function getOwnedAnswers() {
        $user = Auth::user();
        if ($user->isTeacher()) {
            return null;
        };
        return $this->answers->where('student_id', $user->student->id);
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

    function getStatusAttribute() {
        if ($this->finished_at || $this->elapsed >= $this->duration)
            return 'finished';
        if ($this->started_at)
            return 'running';
        if ($this->opened_at)
            return 'opened';
        return 'idle';
    }

    /**
     * Get the final rank
     */
    public function getRank() {
        $sum = 0;
        $questions = $this->getQuestions();
        foreach ($this->getQuestions() as $question) {
            if ($question->answered_at && $question->is_correct) {
                $sum++;
            }
        }
        return round($sum / count($questions) * 5 + 1, 1);
    }

    /**
     * Get questions
     */
    public function getQuestions() {
        return $this->quiz->questions;
    }
}
