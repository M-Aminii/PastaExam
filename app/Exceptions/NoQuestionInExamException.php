<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NoQuestionInExamException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     */
    public function __construct($message = 'هیچ سوالی به این آزمون اضافه نشده است')
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

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
        ], $this->getCode());
    }
}
