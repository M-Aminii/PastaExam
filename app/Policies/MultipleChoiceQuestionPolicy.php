<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MultipleChoiceQuestionPolicy
{
    use HandlesAuthorization;

    public function AccessExam(User $user, Exam $exam)
    {
        return $user->id === $exam->user_id;
    }
}
