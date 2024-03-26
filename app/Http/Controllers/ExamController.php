<?php

namespace App\Http\Controllers;

use App\DTO\ExamDTO;
use App\Events\ExamCreated;
use App\Exceptions\NoActiveExamException;
use App\Exceptions\NoQuestionInExamException;
use App\Http\Requests\Exam\CreateExamRequest;
use App\Http\Requests\Exam\ShowExamRequest;
use App\Models\DescriptiveQuestions;
use App\Models\Exam;
use App\Models\ExamQuestions;
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
        try {
            DB::beginTransaction();
            $user = auth()->user();

            $exam = $user->exams()->latest()->first();
            if (!$exam) {
                throw new NoActiveExamException('هیچ آزمون فعالی برای این کاربر وجود ندارد');
            }
            $questions = $request->input('questions');
            if (!$questions){
                throw new NoQuestionInExamException('هیچ سوالی به این آزمون اضافه نشده است');
            }
            $examQuestion = ExamQuestions::where('exam_id', $exam->id)->first();

            if ($examQuestion) {
                // اگر رکورد وجود داشت، فیلد سوالات را به‌روزرسانی کنید
                $examQuestion->update(['questions_data' => $questions]);
            } else {
                // اگر رکورد وجود نداشت، یک رکورد جدید ایجاد کنید
                ExamQuestions::create([
                    'exam_id' => $exam->id,
                    'questions_data' => $questions
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'سوالات با موفقیت به آزمون اضافه شد'], 201);
        } catch (NoActiveExamException | NoQuestionInExamException $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 400);
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
            return response()->json([
                'message' => 'هیچ آزمون فعالی برای این کاربر وجود ندارد',
            ], 404);
        }

        //$questions = json_decode($exam->examQuestions->first()->questions_data, true);
       $questions = $exam->examQuestions->first()->questions_data;


        $allQuestions = []; // آرایه‌ای برای ذخیره تمامی سوالات

        if ($questions) {
            foreach ($questions as $question) {
                $questionId = $question['id'];
                $questionType = $question['type'];
                if ($questionType === 'multiple_choice') {
                    $result = MultipleChoiceQuestion::find($questionId);

                    $questionForService = [
                        'question_text' => $result->question_text,
                        'option1' => $result->option1,
                        'option2' => $result->option2,
                        'option3' => $result->option3,
                        'option4' => $result->option4,
                        'correct_option' => $result->correct_option,
                        'explanation'=>$result->explanation,
                    ];

                    $questionData = MultipleChoiceQuestionService::getRandomQuestions([$questionForService]);

                } elseif ($questionType === 'descriptive') {
                    $questionData = DescriptiveQuestions::select('question_text','answer','explanation',)->find($questionId);

                }
                $allQuestions[] = $questionData;
            }

            return response()->json([
                'exam_title' => $exam->title,
                'questions' => $allQuestions, // ارسال تمامی سوالات به عنوان یک آرایه
            ], 201);
        } else {
            throw new NoQuestionInExamException('هیچ سوالی به این آزمون اضافه نشده است');

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
