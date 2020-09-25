<?php
namespace App\Transformer;

use League\Fractal;
use Illuminate\Support\Arr;
use App\Models\Activity;

class ActivityTransformer extends Fractal\TransformerAbstract
{
	public function transform(Activity $activity)
	{

	    return [
            'id'         => (int)$activity->id,
            'user_id'    => $activity->user_id,
            'duration'   => $activity->duration,
            'started_at' => $activity->started_at,
            'completed'  => $activity->completed,
            'hidden'    => (int) $activity->hidden,
            'quiz' => [
                'id' => $activity->quiz->id,
                'name' => $activity->quiz->name,
                'questions' => $activity->quiz->questions_count,
                'keywords' => $activity->quiz->keywords
            ],
            'roster' => fractal($activity->roster, new RosterTransformer())->toArray(),
            'created_at' => $activity->created_at,
	    ];
	}
}
