<?php

namespace App\Services;

use App\Models\MultipleChoiceQuestion;

class MultipleChoiceQuestionService
{
    public static function getRandomQuestions($questions)
    {

        $randomizedQuestions = [];

        foreach ($questions as $questionData) {
            $options = [
                $questionData['option1'],
                $questionData['option2'],
                $questionData['option3'],
                $questionData['option4'],
            ];
            shuffle($options);
            $correct_option_index = array_search($questionData['correct_option'], $options);
            $randomizedQuestions[] = [
                //'question_id' => $questionData->id,
                // 'difficulty_level' => $questionData->difficulty_level,
                'question_text' => $questionData['question_text'],
                'options' => $options,
                'correct_option' => $correct_option_index + 1,
                'explanation' => $questionData['explanation'],
            ];
        }

        return $randomizedQuestions;

    }
}
