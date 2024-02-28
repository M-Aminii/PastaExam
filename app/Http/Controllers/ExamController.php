<?php

namespace App\Http\Controllers;

use App\DTO\ExamDTO;
use App\Events\ExamCreated;
use App\Http\Requests\Exam\CreateExamRequest;
use App\Http\Requests\Exam\ShowExamRequest;
use App\Models\Exam;
use App\Models\ExamQuestions;
use App\Models\User;
use App\Services\MultipleChoiceQuestionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
            event(new ExamCreated($Exam));
            DB::commit();
            return response($Exam);

        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }
    public function addQuestionsToExam(Request $request)
    {
        $examId = $request->input('exam_id');
        $questionIds = $request->input('question_ids');


        // دریافت لیست سوالات از QuestionService
        //$questions = MultipleChoiceQuestionService::getRandomQuestions($request->input('textbook_id', 1), $request->input('topic_id', 1));

        foreach ($questionIds as $questionId) {
            $newEntry = new ExamQuestions();
            $newEntry->exam_id = $examId;
            $newEntry->question_id = $questionId;
            $newEntry->save();
        }

        return response()->json(['message' => 'سوالات با موفقیت به آزمون اضافه شد'], 200);
    }
    public function showExamDetails(ShowExamRequest $request)
    {
        $user=$request->user();

        $exam = $user->exams()->with(['questions' => function ($query) {
            $query->select('question_text', 'option1', 'option2', 'option3', 'option4','correct_option','descriptive_answer');
        }])->firstOrFail();

        $randomizedQuestions = MultipleChoiceQuestionService::getRandomQuestions($exam->questions);

        return response()->json([
            'exam_title' => $exam->title,
            'questions' => $randomizedQuestions,
        ], 201);
    }


}
