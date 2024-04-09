<?php

namespace App\Services;

use App\Models\MultipleChoiceQuestion;

class MultipleChoiceQuestionService
{
    /*public static function getRandomQuestions($questions)
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

    }*/

    public static function getRandomQuestions($questions)
    {
        $randomizedQuestions = [];

        foreach ($questions as $questionData) {
            $options = [
                $questionData['option1'],
                $questionData['option2'],
            ];

            // فقط اگر سوال ۴ یا ۵ گزینه داشته باشد، گزینه‌های بیشتر را به آرایه گزینه‌ها اضافه کنید
            if (isset($questionData['option3'])) {
                $options[] = $questionData['option3'];
            }
            if (isset($questionData['option4'])) {
                $options[] = $questionData['option4'];
            }
            if (isset($questionData['option5'])) {
                $options[] = $questionData['option5'];
            }

            // اگر سوال ۴ یا ۵ گزینه‌ای نبود، از تابع shuffle استفاده نکنید و گزینه‌ها جابه‌جا نشوند
            if (count($options) > 2) {
                shuffle($options);
            }

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





    public static function validateQuestionType($request)
    {
        $optionCount = 0;
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($request->input('option' . $i))) {
                $optionCount++;
            }
        }

        $questionType = $request->input('question_type');
        switch ($optionCount) {
            case 2:
                return $questionType === MultipleChoiceQuestion::Two_Options;
            case 4:
                return $questionType === MultipleChoiceQuestion::Four_Options;
            case 5:
                return $questionType === MultipleChoiceQuestion::Five_Options;
            default:
                return false;
        }
    }



}
