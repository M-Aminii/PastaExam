<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterVerifyUserRequest extends FormRequest
{
    use GetRegisterFieldAndValueTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
            'mobile' => ['required_without:email', new MobileRule],
            'email' => 'required_without:mobile|email',
            'name'=> ['required','string'],
            'family'=> ['required','string'],
            'password' => ['required', new PasswordRule],
            'password_confirmation' => ['required'],

        ];
    }
}
