<?php

use Illuminate\Support\Facades\Crypt;

if (!function_exists('encryptId')) {
    function encryptId($id)
    {
        // کلید رمزنگاری را بدست آورید (باید از کلید امن استفاده کنید)
        $key = env('ENCRYPTION_KEY'); // مثلاً کلید رمزنگاری از متغیر محیطی بخوانید

        // رمزنگاری ایدی
        $encryptedId = Crypt::encrypt($id, $key);

        return $encryptedId;
    }
}

if (!function_exists('decryptId')) {
    function decryptId($encryptedId)
    {
        // کلید رمزنگاری را بدست آورید (باید همان کلیدی که برای رمزنگاری استفاده شده است را استفاده کنید)
        $key = env('ENCRYPTION_KEY');

        // رمزگشایی ایدی
        $decryptedId = Crypt::decrypt($encryptedId, $key);

        return $decryptedId;
    }
}
