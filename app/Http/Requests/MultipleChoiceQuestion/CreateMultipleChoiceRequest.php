<?php

namespace App\Http\Requests\MultipleChoiceQuestion;

use App\Models\Exam;
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
            'direction'=>'nullable',
            'difficulty_level'=>'required',
            'question_text' => 'required|string',
            'option1'=> 'required|string',
            'option2'=> 'required|string',
            'option3'=> 'required|string',
            'option4'=> 'required|string',
            'correct_option' => 'required|integer|between:1,4',
            'explanation'=>'nullable|string',
        ];
    }
}
