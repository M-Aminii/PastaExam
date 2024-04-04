<?php

namespace App\Policies;

use App\Exceptions\AuthorizationException;
use App\Models\DescriptiveQuestions;
use App\Models\MultipleChoiceQuestion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DescriptiveQuestionPolicy
{
    use HandlesAuthorization;

    public function delete(User $user,DescriptiveQuestions $question)
    {
        if (!$question->isCreatedBy($user)) {
            throw new AuthorizationException;
        }else{
            return true;
        }
    }
}
