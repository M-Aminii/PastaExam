<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyRegisteredException extends Exception
{
    protected $statusCode = 409;

    public function render($request)
    {
        return response([
            'message' => $this->getMessage()
        ], $this->statusCode);
    }
}
