<?php

namespace App\Policies;

use App\Exceptions\AuthorizationException;
use App\Models\MultipleChoiceQuestion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MultipleChoiceQuestionPolicy
{
    use HandlesAuthorization;

    public function delete(User $user,MultipleChoiceQuestion $question)
    {
        if (!$question->isCreatedBy($user)) {
            throw new AuthorizationException;
        }else{
            return true;
        }
    }
    public function update(User $user,MultipleChoiceQuestion $question)
    {
        if (!$question->isCreatedBy($user)) {
            throw new AuthorizationException;
        }else{
            return true;
        }
    }

}
