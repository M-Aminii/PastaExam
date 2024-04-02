<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NoActiveExamException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     */
    public function __construct($message = 'هیچ آزمون فعالی برای این کاربر وجود ندارد')
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }


    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        // اینجا می‌توانید گزارش خطاها را انجام دهید، مانند ثبت در فایل لاگ
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

