<?php

namespace App\Listeners;

use App\Events\ExamDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteExamQuestions
{
    public function handle(ExamDeleted $event)
    {
        $exam = $event->exam;
        $exam->examQuestions()->delete();
    }
}

