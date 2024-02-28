<?php

namespace App\Http\Requests\Exam;

use App\Models\Exam;
use Illuminate\Foundation\Http\FormRequest;

class ShowExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user=auth()->id();
        $Exam = Exam::where('user_id',$user )->firstOrFail();

        if ( $Exam->user_id == $user){
            return  $Exam->id;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
