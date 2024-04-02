<?php

namespace App\Http\Controllers;

use App\DTO\ExamDTO;
use App\Events\ExamCreated;
use App\Exceptions\NoActiveExamException;
use App\Exceptions\NoQuestionInExamException;
use App\Http\Requests\ExamHeader\CreateExamRequest;
use App\Http\Requests\ExamHeader\ShowExamRequest;
use App\Models\DescriptiveQuestions;
use App\Models\ExamHeader;
use App\Models\Questions;
use App\Models\MultipleChoiceQuestion;
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

class ExamHeaderController extends Controller
{
    public function create(CreateExamRequest $request)
    {
        try {
            DB::beginTransaction();
            $ExamDTO = new ExamDTO($request->validated());
            $Exam = ExamHeader::create((array) $ExamDTO);
            event(new ExamCreated($Exam));
            DB::commit();
            return response(['message' => 'سربرگ آزمون با موفقیت ایجاد شد'], 201);

        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }


    public static function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $request->user();
            $exam = $user->exams()->latest()->first();
            if (!$exam) {
                throw new NoActiveExamException('هیچ آزمون فعالی برای این کاربر وجود ندارد');
            }
            $exam->Delete();
            DB::commit();
            return response(['message' => 'حذف با موفقیت انجام شد'], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'حذف انجام نشد'], 500);

        }

    }



    /*$user=$request->user();

        $exam = $user->exams()->with(['questions' => function ($query) {
            $query->select('question_text', 'option1', 'option2', 'option3', 'option4','correct_option','explanation');
        }])->firstOrFail();

        $randomizedQuestions = MultipleChoiceQuestionService::getRandomQuestions($exam->questions);


        return response()->json([
            'exam_title' => $exam->title,
            'questions' => $randomizedQuestions,
        ], 201);*/

    public function generateWordDocument(Request $request)
    {
        try {
            DB::beginTransaction();

            $templatePath = public_path('templates/exam_template.docx');
            $templateProcessor = new TemplateProcessor($templatePath);

            $user = $request->user();
            $exam = $user->exams()->with(['questions' => function ($query) {
                $query->select('question_text', 'option1', 'option2', 'option3', 'option4','correct_option','explanation');
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
                $templateProcessor->setValue("explanation${questionNumber}", $question['explanation']);
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
