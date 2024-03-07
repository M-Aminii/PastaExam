<?php

namespace App\Http\Requests\Auth;


trait GetRegisterFieldAndValueTrait
{
    public function getFieldName()
    {
        return $this->has('email') ? 'email' : 'mobile';
    }

    public function getFieldValue()
    {
        $field = $this->getFieldName();

        $value = $this->input($field);
        if ($field === 'mobile') {
            $value = '+98' . substr($value, -10, 10);
        }
        return $value;
    }
}
