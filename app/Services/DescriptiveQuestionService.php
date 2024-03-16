<?php

namespace App\Services;

use App\Models\MultipleChoiceQuestion;

class DescriptiveQuestionService
{
    public static function getRandomQuestions($questions)
    {
        $randomizedQuestions = [];

        foreach ($questions as $question) {

            $randomizedQuestions[] = [
                //'question_id' => $question->id,
                'difficulty_level' => $question->difficulty_level,
                'question_text' => $question->question_text,
                'answer_type' => $question->answer_type,
                'answer' => $question->answer,
                'explanation' => $question->explanation,
            ];

        }
        return $randomizedQuestions;
    }
}
