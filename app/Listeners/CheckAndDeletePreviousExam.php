<?php

namespace App\Listeners;

use App\Events\ExamCreated;
use App\Models\ExamHeader;
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

        $user_id = $event->exam->user_id;
        // بررسی وجود امتحان قبلی
        $previousExam = ExamHeader::where('user_id', $user_id)->first();
//dd($event->exam->id);
        // اگر کاربر قبلاً امتحان ایجاد کرده باشد، امتحان جدید را حذف کنید
        if ($previousExam && $previousExam->id !== $event->exam->id) {
            $previousExam->delete();
        }
        // اگر کاربر هنوز امتحانی ایجاد نکرده باشد، امتحان جدید را ذخیره کنید
        if (!$previousExam) {
            $event->exam->save();
        }
    }
}
