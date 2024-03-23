<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidVerificationCodeException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\PasswordMismatchException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendPasswordResetCodeRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
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
    //TODO: برسی تمامی اکسپشن ها

    public function register(RegisterNewUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        if (User::where($field, $value)->first()) {
            throw new UserAlreadyRegisteredException('شما قبلا ثبت نام کرده اید',409);
        }

        $code =123456;
                    //random_verification_code();

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
            $name =$request->name;
            $family =$request->family;
            $password = $request->password;
            $passwordConfirmation = $request->password_confirmation;

            $registerData = Cache::get('user-auth-register-' . $value);

            if (!$registerData || $registerData['code'] != $code) {
                throw new InvalidVerificationCodeException('کد تایید نامعتبر است',422);
            }

            $Uesr =User::where($registerData['field'], $value)->first();
            if ($Uesr) {
                throw new UserAlreadyRegisteredException('شما قبلا ثبت نام کرده اید',409);
            }

                    // بررسی معتبر بودن رمز عبور و تکرار آن
            if ($password !== $passwordConfirmation) {
                throw new PasswordMismatchException('رمز عبور و تکرار آن باید یکسان باشند.',422);
            }

            $user = User::create([
                'email' => $registerData['field'] == 'email' ? $value : null,
                'mobile' =>$registerData['field'] == 'mobile' ? $value : null,
                'name'=>$name,
                'family'=>$family,
                'password'=> bcrypt($password),
            ]);

            Cache::forget('user-auth-register-' . $value);

            DB::commit();
            return response([
                'message' => 'ثبت نام با موفقیت به اتمام رسید',
                'کاربر'=> $user
            ], 201);

        } catch (InvalidVerificationCodeException | UserAlreadyRegisteredException | PasswordMismatchException $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], $exception->getCode());
        } catch (Exception $exception) {
            DB::rollBack();
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }

    }

    public function resendPasswordResetCode(ResendPasswordResetCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        // بررسی وجود کاربر
        $user = User::where($field, $value)->first();
        if (!$user) {
            return response()->json(['message' => 'کاربری با این مشخصات یافت نشد'], 404);
        }

        // ارسال کد تایید
        $code = 123456; // یا تابع random_verification_code()
        $expiration = config('auth.register_cache_expiration', 200000000);
        Cache::put('user-auth-verification-' . $value, compact('code', 'field'), now()->addMinutes($expiration));

        //TODO: ارسال ایمیل یا پیامک به کاربر
        //TODO: اضافه کردن تاریخ انقضاء
        Log::info('SEND-VERIFICATION-CODE-MESSAGE-TO-USER', ['code' => $code]);

        if ($field==='mobile'){
            return response(['message' => 'کد بازیابی رمز عبور به شماره موبایل شما ارسال شد'], 200);
        }else{
            return response(['message' => 'کد بازیابی رمز عبور به ایمیل شما ارسال شد'], 200);
        }    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $value = $request->getFieldValue();
            $code = $request->code;
            $password = $request->password;
            $passwordConfirmation = $request->password_confirmation;

            $registerData = Cache::get('user-auth-verification-' . $value);

                if ( !$registerData || $registerData['code'] != $code) {
                    throw new InvalidVerificationCodeException('کد تایید نامعتبر است',422);
                }

                $uesr = User::where($registerData['field'], $value)->first();
                if (!$uesr) {
                    return response()->json(['message' => 'کاربری با این مشخصات یافت نشد'], 404);                }
                    // بررسی معتبر بودن رمز عبور و تکرار آن
                if ($password !== $passwordConfirmation) {
                    throw new PasswordMismatchException('رمز عبور و تکرار آن باید یکسان باشند.',422);
                }

                $uesr->password =bcrypt($password) ;
                $uesr->save();

                Cache::forget('user-auth-verification-' . $value);
                DB::commit();
                return response([
                    'message' => 'رمز عبور جدید وارد شد ',
                ], 200);

        } catch (InvalidVerificationCodeException |UserAlreadyRegisteredException| PasswordMismatchException $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], $exception->getCode());
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }
    }

    public static function resendVerificationCodeToUser(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->first();

        if (empty($user)) {

            $code = 123456;
                //random_int(100000, 999999);
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
