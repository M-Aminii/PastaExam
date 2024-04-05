<?php

namespace App\Http\Requests\DescriptiveQuestion;

use App\Exceptions\NoQueryResultsException;
use App\Models\DescriptiveQuestions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class updateDescriptiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        $question = DescriptiveQuestions::find($this->question);
        if (!$question) {
            // ارسال پاسخ با خطای 404 به کاربر
            throw new NoQueryResultsException;
        }
        return Gate::allows('update-Descriptive', $question);
    }

    public function rules(): array
    {
        //TODO:برسی کردن رول ها
        return [
            'grade_level_id'=>'required|exists:grade_levels,id',
            'grade_id'=>'required|exists:grades,id',
            'field_id'=>'nullable|exists:fields,id',
            'textbook_id'=>'required|exists:textbooks,id',
            'topic_id'=>'required|exists:topics,id',
            'source'=>'nullable|string',
            'answer_type'=>'required|in:short,full',
            'direction'=>'nullable',
            'difficulty_level'=>'required',
            'question_text' => 'required|string',
            'answer' => 'required|string',
            'explanation'=>'nullable|string',
        ];
    }

}
