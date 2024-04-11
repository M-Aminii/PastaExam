<?php

namespace App\Http\Requests\DescriptiveQuestion;

use App\Models\DescriptiveQuestions;
use App\Models\Exam;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CreateDescriptiveQuestionRequest extends FormRequest
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
            'answer_type'=>'required|in:' . implode(',', DescriptiveQuestions::Type),
            'direction' => 'nullable|in:' . implode(',', DescriptiveQuestions::Direction),
            'difficulty_level'=>'required|in:' . implode(',', DescriptiveQuestions::LEVEL),
            'question_text' => 'required|string',
            'answer' => 'required|string',
            'explanation'=>'nullable|string',
        ];
    }
}
