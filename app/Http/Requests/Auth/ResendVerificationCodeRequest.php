<?php

namespace App\Http\Requests\Auth;

use App\Rules\EmailRule;
use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class ResendVerificationCodeRequest extends FormRequest
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
            'mobile' => ['required_without:email', new MobileRule],
            'email' => ['required_without:mobile', 'string', 'email', 'max:100', 'unique:users,email' ,new EmailRule],
        ];
    }
}
