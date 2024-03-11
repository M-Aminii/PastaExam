<?php

namespace App\Http\Requests\user;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class infoRequest extends FormRequest
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
            'name'=> ['required','string'],
            'lastname'=> ['required','string'],
            'gender'=> ['required','string'],
            'password' => ['required', new PasswordRule],
            'password_confirmation' => ['required'],
        ];
    }
}
