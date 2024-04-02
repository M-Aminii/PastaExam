<?php

namespace App\Policies;

use App\Models\DescriptiveQuestions;
use App\Models\ExamHeader;
use App\Models\MultipleChoiceQuestion;
use App\Models\User;
use App\Services\MultipleChoiceQuestionService;

class QuestionPolicy
{
    public function existQuestions(User $user)
    {
        $exam = $user->exams()->latest()->first();
        $questions = $exam->examQuestions->first()->questions_data;
        if ($questions)
        {
            foreach ($questions as $question) {
                $questionId = $question['id'];
                $questionType = $question['type'];
                if ($questionType === 'multiple_choice') {
                    $result = MultipleChoiceQuestion::find($questionId);

                }
                elseif ($questionType === 'descriptive') {
                    $questionData = DescriptiveQuestions::find($questionId);

                }

            }

        }
    }
}
