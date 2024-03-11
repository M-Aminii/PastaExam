<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\user\ChangePasswordRequest;
use App\Http\Requests\user\ChoiceRoleRequest;
use App\Http\Requests\user\infoRequest;
use App\Http\Requests\user\ProfileRequest;
use App\Services\PasswordService;
use App\Services\UpdateUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function info(infoRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->gender = $request->gender;
            $user->save();
            // فراخوانی متد setPassword از سرویس PasswordService
            $passwordService = new PasswordService();
            $passwordServiceResponse = $passwordService->setPassword($request->password, $request->password_confirmation);

            // بررسی نتیجه تغییر رمز عبور
            if ($passwordServiceResponse->getStatusCode() !== 200) {
                return $passwordServiceResponse; // اگر تغییر رمز عبور با موفقیت صورت نگرفته باشد، پاسخ خطا را برگردانید
            }
            DB::commit();
            return response(['message' => 'مشخصات فردی ثبت شد'], 200);
        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }

    }


    public function profile(ProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $userDTO = new UserDTO($request->validated());
            UpdateUserService::updateUser($userDTO);
            DB::commit();
            return response(['message' => 'مشخصات فردی بروزرسانی شد'], 200);
        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }

    }
    public static function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();

            if (!Hash::check($request->old_password, $user->password)) {
                return response(['message' => 'گذر واژه وارد شده مطابقت ندارد'], 400);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return response(['message' => 'تغییر گذرواژه با موفقیت انجام شد'], 200);
        } catch (Exception $exception) {
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }
    }

}
