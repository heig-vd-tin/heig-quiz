<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Answer;

class AnswerUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;

    }

    public function broadcastWith()
    {
        return [
            'student_id' => $this->answer->student_id
        ];
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('activity'),
            new PrivateChannel("activity.{$this->answer->activity_id}.teacher"),
            new PresenceChannel("activity.{$this->answer->activity_id}")
        ];
    }
}
