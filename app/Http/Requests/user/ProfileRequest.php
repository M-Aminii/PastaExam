<?php

namespace App\Http\Requests\user;

use App\Rules\EmailRule;
use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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

            'role' => ['required', 'string', 'in:teacher,student'],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username'],
            'name' => ['required', 'string', 'max:100'],
            'family' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'string', 'in:man,woman'],
            'birthdate' => ['nullable', 'date'],
            //'national_code' => ['nullable', 'string', 'digits:10', 'unique:users,national_code'],
            'grade_level_id' => ['nullable', 'integer', 'exists:grade_levels,id'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'avatar' => ['nullable', 'image', 'max:1024'],
            'about_me' => ['nullable', 'string','max:255']
        ];
    }
}
