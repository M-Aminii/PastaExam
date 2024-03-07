<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterNewUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();


        $code = random_verification_code();
        $expiration = config('auth.register_cache_expiration', 200000000);
        Cache::put('user-auth-register-' . $value, compact('code', 'field'),now()->addMinutes($expiration));

        //TODO: ارسال ایمیل یا پیامک به کاربر
        //TODO: اضافه کردن تاریخ انقضاء
        Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
        if ($field==='mobile'){
            return response(['message' => 'کد احراز هویت به شماره موبایل شما ارسال شد'], 200);
        }else{
            return response(['message' => 'کد احراز هویت به ایمیل شما ارسال شد'], 200);
        }

    }
    public function registerVerify(RegisterVerifyUserRequest $request)
    {

        try {
            DB::beginTransaction();
            $value = $request->getFieldValue();
            $code = $request->code;

            $registerData = Cache::get('user-auth-register-' . $value);

            if ($registerData){
                $Uesr =User::where($registerData['field'], $value)->first();
                if ($Uesr) {
                    //TODO: برطرف کردن مشکل پیغام
                    throw new UserAlreadyRegisteredException('شما قبلا ثبت نام کرده اید');
                }
                if ($registerData && $registerData['code'] == $code){
                    $user = User::create([
                        'email' => $registerData['field'] == 'email' ? $value : null,
                        'mobile' =>$registerData['field'] == 'mobile' ? $value : null,
                    ]);
                    DB::commit();
                    return response([
                        'message' => 'ثبت نام با موفقیت به اتمام رسید',
                        'کاربر'=> $user
                    ], 201);
                }
            }
        }catch (Exception $exception){
            Db::rollBack();
            throw new UserAlreadyRegisteredException('کد تاییده وارد شده اشتباه می باشد یا زمان ان به انمام رسیده است  ');
        }



    }

    public static function resendVerificationCodeToUser(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->first();

        if (empty($user)) {

            $code = random_int(100000, 999999);
            $expiration = config('auth.register_cache_expiration', 2);
            Cache::put('user-auth-register-' . $value, compact('code', 'field'),now()->addMinutes($expiration));

            //TODO: ارسال ایمیل یا پیامک به کاربر
            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);

            return response([
                'message' => 'کد مجدداً برای شما ارسال گردید',
            ], 200);
        }

        throw new ModelNotFoundException('کاربری با این مشخصات یافت نشد یا قبلا فعالسازی شده است');
    }


}
