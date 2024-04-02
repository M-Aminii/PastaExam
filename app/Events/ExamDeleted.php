<?php

namespace App\Events;

use App\Http\Controllers\ExamHeaderController;
use App\Models\ExamHeader;
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

    public function __construct(ExamHeader $exam)
    {
        $this->exam = $exam;
    }
}
