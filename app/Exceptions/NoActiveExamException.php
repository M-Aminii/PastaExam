<?php

namespace App\Exceptions;

use Exception;

class NoActiveExamException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 404);
    }
}
