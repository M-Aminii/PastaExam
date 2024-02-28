<?php

namespace App\Listeners;

use App\Events\ExamCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckAndDeletePreviousExam
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExamCreated $event): void
    {

        $user = $event->exam->user;
        $previousExam = $user->exams()->first();
        if ($previousExam) {
            $previousExam->delete();
        }
    }
}
