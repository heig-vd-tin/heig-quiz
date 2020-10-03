<?php
namespace App\Transformer;

use League\Fractal;
use App\Models\Question;
use Auth;

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
        // Common fields
	    $output = [
            'id' => $question->id,
            'name' => $question->name,
            'content' => $question->content,
            'options' => $question->options,
            'type' => $question->type
        ];

        // Only for teachers
        if ($this->is_teacher) {
            $output = array_merge($output, [
                'validation' => $question->validation,
                'created_at' => $question->created_at,
                'updated_at' => $question->updated_at,
                'difficulty' => $question->difficulty,
            ]);
        }

        // Only for students
        if ($this->is_student) {
            $output = array_merge($output, [
                'number' => $question->question_number,
            ]);
        }

        // Available to students once answered
        if ($this->is_student and $question->answer) {
            $output = array_merge($output, [
                'answered_at' => $question->answer->updated_at,
                'answered' => $question->answer->answer,
            ]);
        }

        // Available to students once finished
        if ($this->is_student and $this->activity and $this->activity->status == 'finished') {
            if ($question->answer) {
                $output = array_merge($output, [
                    'answered_at' => $question->answer->updated_at,
                    'answered' => $question->answer->answer,
                    'is_correct' => $question->answer->is_correct,
                ]);
            }

            $output = array_merge($output, [
                'explanation' => $question->explanation,
                'statistics' => $question->statistics,
                'validation' => $question->validation
            ]);
        }

        // Only for students
        if ($this->is_student and $this->activity) {
            if ($question->next_question)
                $output['next_question_url'] = url("/api/activities/{$this->activity->id}/questions/{$question->next_question}");
            if ($question->previous_question)
                $output['previous_question_url'] = url("/api/activities/{$this->activity->id}/questions/{$question->previous_question}");
        }

        return $output;
	}
}
