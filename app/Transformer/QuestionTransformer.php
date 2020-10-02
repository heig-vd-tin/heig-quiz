<?php
namespace App\Transformer;

use League\Fractal;
use App\Models\Question;

class QuestionTransformer extends Fractal\TransformerAbstract
{
    protected $activity;
    protected $is_teacher;
    protected $is_student;

    public function __construct($activity = null)
    {
        $this->activity = $activity;
        $this->is_teacher = Auth::user()->isTeacher();
        $this->is_student = Auth::user()->isStudent();
    }

	public function transform(Question $question)
	{
        // Public properties
	    $output = [
            'id' => $question->id,
            'name' => $question->name,
            'content' => $question->content,
            'options' => $question->options,
        ];

        // Only for teachers
        $teacher = [
            'validation' => $question->validation, // Only teacher
            'created_at' => $question->created_at, // Only teacher
            'updated_at' => $question->updated_at,
            'difficulty' => $question->difficulty, // Only teacher and student if finished
        ];

        // Available to students once answered
        $answered = [
            'answered_at' => $question->answered_at,
            'answered' => $question->answered,
        ];

        // Available to students once finished
        $finished = [
            'answered_at' => $question->answered_at,
            'answered' => $question->answered,
            'is_correct' => $question->is_correct,
            'explanation' => $question->explanation,
            'statistics' => [
                'correct_answers' => $question->stats->correct,
                'incorrect_answers' => $question->stats->incorrect,
                'unanswered' => $question->stats->unanswered,
            ],
        ];

        $is_answered = $this->is_student and $question->answered_at != null;
        $is_finished = $this->is_student and
            $this->activity and
            $this->activity->status == 'finished';

        if ($this->is_teacher) $output = array_merge($output, $teacher);
        if ($is_answered) $output = array_merge($output, $answered);
        if ($is_finished) $output = array_merge($output, $finished);

        ksort($output);

        return $output;
	}
}
