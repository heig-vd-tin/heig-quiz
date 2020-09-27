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
     * For the current activity, returns the question order in
     * which they will be generated for the given student_id.
     */
    function getQuestionsOrder($student_id) {
        $hash = hash('sha1', "$this->id $this->quiz_id $student_id");
        $seed = unpack("L", substr($hash, 0, 4))[1];
        $count = $this->quiz->questions_count;

        mt_srand($seed , MT_RAND_MT19937);

        $array = range(0, $count - 1);
        for ($i = 0; $i < $count; ++$i) {
            list($chunk) = array_splice($array, mt_rand(0, $count - 1), 1);
            array_push($array, $chunk);
        }

        return $array;
    }

    /**
     * Get the questions with the student's answers only if the activity
     * is finished.
     */
    public function getQuestions() {
        $answers = $this->status == 'finished' ? $this->getOwnedAnswers() : null;
        $questions = $this->quiz->questions->each(function ($question) use ($answers) {
            if ($answers) {
                foreach ($answers as $answer) {
                    if ($answer->question_id == $question->id) {
                        $question['answered_at'] = $answer->created_at;
                        $question['answered'] = $answer->answer;
                        $question['is_correct'] = $answer->is_correct;
                        continue;
                    }
                }
            }
        });
        return $questions;
    }
}
