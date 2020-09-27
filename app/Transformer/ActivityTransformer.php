<?php
namespace App\Transformer;

use League\Fractal;
use Illuminate\Support\Arr;
use App\Models\Activity;

class ActivityTransformer extends Fractal\TransformerAbstract
{
	public function transform(Activity $activity)
	{

	    $data = [
            'id'         => (int)$activity->id,
            'user_id'    => $activity->user_id,
            'duration'   => $activity->duration,
            'started_at' => $activity->started_at,
            'completed'  => $activity->completed,
            'hidden'    => (int) $activity->hidden,
            'status' => $activity->status,
            'quiz' => [
                'id' => $activity->quiz->id,
                'name' => $activity->quiz->name,
                'questions' => $activity->quiz->questions_count,
                'keywords' => $activity->quiz->keywords
            ],
            'roster' => fractal($activity->roster, new RosterTransformer())->toArray(),
            'created_at' => $activity->created_at,

            'quiz_url' => url("/api/quizzes/{$activity['quiz_id']}"),
            'roster_url' => url("/api/rosters/{$activity['roster_id']}"),
            'questions_url' => url("/api/activities/{$activity['id']}/questions"),
        ];

        if ($activity->hidden)
            $data['@show_url'] = url("/api/activities/{$activity['id']}/show");
        else
            $data['@hide_url'] = url("/api/activities/{$activity['id']}/hide");

        if (!$activity->started && !$activity->completed)
            $data['@start_url'] = url("/api/activities/{$activity['id']}/start");

        return $data;
	}
}
