<?php

namespace App\Exceptions;

use Exception;

class InvalidVerificationCodeException extends Exception
{
    protected $statusCode = 422;

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response([
            'message' => $this->getMessage()
        ], $this->statusCode);
    }
}
