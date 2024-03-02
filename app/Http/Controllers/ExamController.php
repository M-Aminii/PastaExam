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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

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
    public function generateWordDocument(Request $request)
    {
        try {
            DB::beginTransaction();

            $templatePath = public_path('templates/exam_template.docx');
            $templateProcessor = new TemplateProcessor($templatePath);

            $user = $request->user();
            $exam = $user->exams()->with(['questions' => function ($query) {
                $query->select('question_text', 'option1', 'option2', 'option3', 'option4','correct_option','descriptive_answer');
            }])->firstOrFail();

            $questions = $exam->questions;
            $randomizedQuestions = MultipleChoiceQuestionService::getRandomQuestions($questions);

            foreach ($randomizedQuestions as $index => $question) {
                $questionNumber = $index + 1;

                $questionText = $question['question_text'];
                $options = $question['options'];
                $correctOption = $question['correct_option'];

                $templateProcessor->setValue("question_title_${questionNumber}", $questionText);
                $templateProcessor->setValue("option_a_${questionNumber}", $options[0]);
                $templateProcessor->setValue("option_b_${questionNumber}", $options[1]);
                $templateProcessor->setValue("option_c_${questionNumber}", $options[2]);
                $templateProcessor->setValue("option_d_${questionNumber}", $options[3]);
                $templateProcessor->setValue("correct_option_${questionNumber}",$correctOption);
                $templateProcessor->setValue("descriptive_answer_${questionNumber}", $question['descriptive_answer']);
            }

            $outputPath = public_path('exam_documents/generated_exam.docx');
            $templateProcessor->saveAs($outputPath);

            DB::commit();

            return response()->json(['filename' => $outputPath,], 201);
            // return response()->download($outputPath)->deleteFileAfterSend(true);
        }catch (Exception $exception) {
        DB::rollBack();
        Log::error($exception);
        return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }





}
