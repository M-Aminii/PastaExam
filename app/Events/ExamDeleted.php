<?php

namespace App\Events;

use App\Models\Exam;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamDeleted
{
    use Dispatchable, SerializesModels;

    public $exam;

    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }
}
