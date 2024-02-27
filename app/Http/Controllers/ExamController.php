<?php

namespace App\Http\Controllers;

use App\DTO\ExamDTO;
use App\Http\Requests\Exam\CreateExamRequest;
use App\Models\Exam;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function create(CreateExamRequest $request)
    {
        try {
            DB::beginTransaction();
            $ExamDTO = new ExamDTO($request->validated());
            $Exam = Exam::create((array) $ExamDTO);
            DB::commit();
            return response($Exam);

        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }
}
