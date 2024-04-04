<?php

namespace App\Http\Requests\MultipleChoiceQuestion;

use App\Exceptions\NoQueryResultsException;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DeleteMultipleChoiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        $question = MultipleChoiceQuestion::find($this->question);
        if (!$question) {
            // ارسال پاسخ با خطای 404 به کاربر
            throw new NoQueryResultsException;
        }
        return Gate::allows('delete-Multiple-Choice', $question);
    }

    public function rules(): array
    {
        return [
        ];
    }

}
