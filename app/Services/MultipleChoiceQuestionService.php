<?php

namespace App\Services;

use App\Models\MultipleChoiceQuestion;

class MultipleChoiceQuestionService
{
    public static function getRandomQuestions($questions)
    {
        $randomizedQuestions = [];

        foreach ($questions as $question) {
            $options = [
                $question->option1,
                $question->option2,
                $question->option3,
                $question->option4,
            ];
            shuffle($options);
            $correct_option_index = array_search($question->correct_option, $options);
            $randomizedQuestions[] = [
                //'question_id' => $question->id,
               // 'difficulty_level' => $question->difficulty_level,
                'question_text' => $question->question_text,
                'options' => $options,
                'correct_option' => $correct_option_index + 1,
                'descriptive_answer' => $question->descriptive_answer,
            ];
        }

        return $randomizedQuestions;
    }
}
