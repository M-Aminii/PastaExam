<?php

namespace App\Exceptions;

use Exception;

class PasswordMismatchException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        // اینجا می‌توانید خطا را گزارش دهید، اگر نیاز دارید.
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(['message' => 'رمز عبور و تکرار آن باید یکسان باشند'], 400);
    }
}
