<?php

namespace App\Http\Requests\MultipleChoiceQuestion;

use App\Models\Exam;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CreateMultipleChoiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'grade_level_id'=>'required|exists:grade_levels,id',
            'grade_id'=>'required|exists:grades,id',
            'field_id'=>'nullable|exists:fields,id',
            'textbook_id'=>'required|exists:textbooks,id',
            'topic_id'=>'required|exists:topics,id',
            'source'=>'nullable|string',
            'question_type'=>'required|in:' . implode(',', MultipleChoiceQuestion::Type),
            'direction' => 'nullable|in:' . implode(',', MultipleChoiceQuestion::Direction),
            'difficulty_level'=>'required|in:' . implode(',', MultipleChoiceQuestion::LEVEL),
            'question_text' => 'required|string',
            'option1'=> 'required|string',
            'option2'=> 'required|string',
            'option3'=> 'nullable|string',
            'option4'=> 'nullable|string',
            'option5'=> 'nullable|string',
            'correct_option' => 'required|integer|between:1,4',
            'explanation'=>'nullable|string',
        ];
    }

}






