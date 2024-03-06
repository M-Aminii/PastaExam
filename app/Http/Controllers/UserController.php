<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\user\ChoiceRoleRequest;
use App\Http\Requests\user\infoRequest;
use App\Http\Requests\user\TeacherInfoRequest;
use App\Services\UpdateUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function choiceRole(ChoiceRoleRequest $request)
    {
        try {
            $user = auth()->user();
            $user->role = $request->role;
            $user->save();
            return response(['message' => 'نقش مورد نظر انتخاب شد'], 200);

        }catch (Exception $exception) {
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }

    }

    public function info(infoRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->gender = $request->gender;
            $user->save();
            DB::commit();

            return response(['message' => 'مشخصات فردی بروزرسانی شد'], 200);
        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }

    }

    public function studentInfo(StudentInfoRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();


            $user->save();
            DB::commit();

            return response(['message' => 'مشخصات فردی بروزرسانی شد'], 200);
        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی به وجود آمده است'], 500);
        }

    }

    public function teacherInfo(TeacherInfoRequest $request)
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

}
