<?php

namespace App\Http\Controllers;

use App\Exceptions\NoActiveExamException;
use App\Exceptions\NoQuestionInExamException;
use App\Http\Requests\ExamHeader\ShowExamRequest;
use App\Http\Requests\Questions\AddQuestionsToExam;
use App\Models\Questions;
use App\Services\QuestionService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{

    public function addQuestionsToExam(AddQuestionsToExam $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $exam = $user->exams()->latest()->first();
            if (!$exam) {
                throw new NoActiveExamException;
            }
            $questions = $request->input('questions');
            if (!$questions){
                throw new NoQuestionInExamException;
            }
            $newQuestions = QuestionService::existQuestions($questions);
            Questions::updateOrCreate(
                ['exam_id' => $exam->id], // جستجو بر اساس exam_id
                ['questions_data' => $newQuestions] // اطلاعات جدید برای ذخیره
            );
            DB::commit();
            return response()->json(['message' => 'سوالات با موفقیت به آزمون اضافه شد'], 201);
        } catch (NoActiveExamException | NoQuestionInExamException $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], $exception->getCode());
        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }



    public function showExamDetails(ShowExamRequest $request)
    {
        $user = auth()->user();
        $exam = $user->exams()->latest()->first();

        if (!$exam) {
            throw new NoActiveExamException;
        }
        if (!$exam->examQuestions->first() || !$exam->examQuestions->first()->questions_data) {
            throw new NoQuestionInExamException;
        }
        //$questions = json_decode($exam->examQuestions->first()->questions_data, true);
        $questions = $exam->examQuestions->first()->questions_data;
// فراخوانی متد selectQuestions با ارسال آرایه سوالات به آن
        $allQuestions = QuestionService::selectQuestions($questions);


            return response()->json([
                'exam_title' => $exam->title,
                'questions' => $allQuestions, // ارسال تمامی سوالات به عنوان یک آرایه
            ], 201);

    }
}
