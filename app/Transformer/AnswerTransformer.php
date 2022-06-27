<?php
namespace App\Transformer;

use League\Fractal;
use App\Models\Answer;

class AnswerTransformer extends Fractal\TransformerAbstract
{
    protected $activity;

	public function transform(Answer $answer)
	{
        // Public properties
	    $output = [
            'id' => $answer->id,
            'activity_id' => $answer->activity_id,
            'student_id' => $answer->student_id,
            'question_id' => $answer->question_id,
        ];

        // Available to students once answered
        $answered = [
            'answered_at' => $answer->updated_at,
            'answered' => $answer->answer,
            'point' => $answer->points,
        ];

        // Available to students once finished
        $finished = [
            'answered_at' => $answer->answered_at,
            'answered' => $answer->answered,
            'is_correct' => $answer->is_correct,
            'explanation' => $answer->explanation,
            'statistics' => [
                'correct_answers' => $answer->stats->correct,
                'incorrect_answers' => $answer->stats->incorrect,
                'unanswered' => $answer->stats->unanswered,
            ],
        ];

        $is_teacher = Auth::user()->isTeacher();
        $is_answered = Auth::user()->isStudent() and $answer->answered_at != null;
        $is_finished = Auth::user()->isStudent() and
            $this->activity and
            $this->activity->status == 'finished';

        if ($is_teacher) $output = array_merge($output, $teacher);
        if ($is_answered) $output = array_merge($output, $answered);
        if ($is_finished) $output = array_merge($output, $finished);

        ksort($output);

        return $output;
	}
}
