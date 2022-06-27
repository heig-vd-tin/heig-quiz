<?php
namespace App\Transformer;

use League\Fractal;
use App\Models\Quiz;
use Carbon\Carbon;

class QuizTransformer extends Fractal\TransformerAbstract
{
	public function transform(Quiz $quiz)
	{
	    return [
            'id'         => (int)$quiz->id,
            'name'       => $quiz->name,
            'questions' => $quiz->questions_count,
            'difficulty' => $quiz->difficulty,
            'taken_times' => $quiz->taken,
            'owner' => [
                'id' => $quiz->owner->id,
                'name' => $quiz->owner->name
            ],
            'keywords' => $quiz->keywords,
            'is_exam' => $quiz->is_exam,
            'created_at' => $quiz->created_at->timestamp,
            'updated_at' => $quiz->updated_at->timestamp,
            

            // Links
            'quiz_url' => url("api/quizzes/$quiz->id"),
            'questions_url' => url("api/quizzes/$quiz->id/questions"),
            'activities_url' => url("api/quizzes/$quiz->id/activities"),
	    ];
	}
}
