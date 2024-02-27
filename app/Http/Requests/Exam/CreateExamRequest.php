<?php

namespace App\Http\Requests\Exam;

use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\FormRequest;

class CreateExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'grade_level_id' => 'required|integer',
            'grade_id' => 'required|integer',
            'field_id' => 'integer',
            'textbook_id' => 'required|integer',
            'topic_id' => 'required|integer',
            'region_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'school_id' => 'nullable|integer',
            'negative_mark'=>'nullable|integer',
            'date_time' => 'nullable|date_format:Y-m-d H:i:s|after:now',
            'difficulty_level' => 'required|string',
            'duration_minutes' => 'required|integer',
            'exam_type' => 'required',
            'year' => 'integer',
            'month' => 'integer',
        ];
    }
}
