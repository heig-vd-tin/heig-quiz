<?php

namespace App\Events;

use App\Transformer\QuizTransformer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Quiz;

class QuizCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function broadcastWith()
    {
        return fractal($this->quiz->get(), new QuizTransformer())->toArray();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('quiz');
    }
}
