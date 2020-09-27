<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;

    public function __construct($activity = null)
    {
        $this->activity = $activity;
    }

    public function broadcastWith()
    {
        return ['id' => $this->activity->id];
    }

    public function broadcastOn()
    {
        $channels = [new PrivateChannel('activity')];

        if ($this->activity != null)
            $channels[] = new PresenceChannel("activity.{$this->activity->id}");

        return $channels;
    }
}
