<?php

namespace App\Services;

use App\Exceptions\NoQuestionInExamException;
use App\Models\DescriptiveQuestions;
use App\Models\MultipleChoiceQuestion;

class QuestionService
{
    public static function selectQuestions($questions)
    {
        $allQuestions = []; // آرایه‌ای برای ذخیره تمامی سوالات

        if ($questions)
        {
            // اضافه کردن سوالات به آرایه $allQuestions
            foreach ($questions as $question) {
                $questionId = $question['id'];
                $questionType = $question['type'];
                if ($questionType === 'multiple_choice') {
                    $result = MultipleChoiceQuestion::where('id', $questionId)->first();

                    if ($result) {
                        $questionForService = [
                            'id' => $result->id,
                            'question_text' => $result->question_text,
                            'option1' => $result->option1,
                            'option2' => $result->option2,
                            'option3' => $result->option3 ?? null,
                            'option4' => $result->option4 ?? null,
                            'option5' => $result->option5 ?? null,
                            'correct_option' => $result->correct_option,
                            'explanation' => $result->explanation,
                        ];

                        $questionData = MultipleChoiceQuestionService::getRandomQuestions([$questionForService]);
                        $allQuestions[] = reset($questionData);
                    }

                } elseif ($questionType === 'descriptive') {
                    $result = DescriptiveQuestions::where('id', $questionId)->first();

                    if ($result) {
                        $questionData = [
                            'id' => $result->id,
                            'question_text' => $result->question_text,
                            'answer' => $result->answer,
                            'explanation' => $result->explanation,
                        ];

                        $allQuestions[] = $questionData;
                    }
                }
            }
            return $allQuestions;
        }
        else {
            throw new NoQuestionInExamException;

        }
    }
    public static function existQuestions($newQuestions)
    {
        foreach ($newQuestions as $newQuestion) {
            $questionId = $newQuestion['id'];
            $questionType = $newQuestion['type'];

            // بررسی وجود سوال در جدول مربوطه
            $questionExists = false;
            if ($questionType === 'multiple_choice') {
                $questionExists = MultipleChoiceQuestion::find($questionId) !== null;
            } elseif ($questionType === 'descriptive') {
                $questionExists = DescriptiveQuestions::find($questionId) !== null;
            }
            // اگر سوال وجود داشت، آن را به آرایه‌ی سوالات موجود اضافه کنید
            if ($questionExists) {
                // اضافه کردن سوال به آرایه‌ی سوالات موجود
                $existingQuestions[] = $newQuestion;
            }
        }
        return $existingQuestions;
    }

}
