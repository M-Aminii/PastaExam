<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UpdateUserService
{
    public static function updateUser($requestData)
    {
        $user = Auth::user(); // یافتن کاربر فعلی

        // چک کردن تغییرات در فیلدهای مورد نیاز
        $changedFields = array();
        foreach ($requestData as $key => $value) {
            if ($user->{$key} != $value) {
                $changedFields[] = $key;
            }
        }

        // داده‌های UserDTO را به آرایه تبدیل کنید
        $userDataArray = (array) $requestData;

        // فیلدهای جدید را به روز کنید
        $user->fill($userDataArray);

        // ذخیره تغییرات در دیتابیس
        $user->save();

        return $changedFields; // بازگشت فیلدهایی که تغییر کرده‌اند
    }


}
