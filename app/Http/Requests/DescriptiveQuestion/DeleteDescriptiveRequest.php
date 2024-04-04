<?php

namespace App\Http\Requests\DescriptiveQuestion;

use App\Exceptions\NoQueryResultsException;
use App\Models\DescriptiveQuestions;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DeleteDescriptiveRequest extends FormRequest
{

    public function authorize(): bool
    {
        $question = DescriptiveQuestions::find($this->question);
        if (!$question) {
            // ارسال پاسخ با خطای 404 به کاربر
            throw new NoQueryResultsException;
        }
        return Gate::allows('delete-Descriptive', $question);
    }

    public function rules(): array
    {
        return [
        ];
    }

}
