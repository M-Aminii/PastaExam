<?php

namespace App\Http\Controllers;

use App\DTO\DescriptivQuestionDTO;
use App\DTO\QuestionDTO;

use App\Http\Requests\DescriptiveQuestion\CreateDescriptiveQuestionRequest;
use App\Http\Requests\DescriptiveQuestion\DeleteDescriptiveRequest;
use App\Http\Requests\DescriptiveQuestion\updateDescriptiveRequest;
use App\Models\DescriptiveQuestions;
use App\Models\Exam;
use App\Models\MultipleChoiceQuestion;
use App\Services\DescriptiveQuestionService;
use App\Services\MultipleChoiceQuestionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DescriptiveQuestionController extends Controller
{

    public function create(CreateDescriptiveQuestionRequest $request)
    {

        try {
            DB::beginTransaction();
            $questionDTO = new DescriptivQuestionDTO($request->validated());
            DescriptiveQuestions::create((array) $questionDTO);
            DB::commit();
            return response()->json(['message' => 'سوال با موفقیت اضافه شد'], 201);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }


    public static function ListDescriptive(Request $request)
    {

        $questions = DescriptiveQuestions::with(['book', 'topic'])
            ->where('textbook_id', $request->input('textbook_id', 1))
            ->where('topic_id', $request->input('topic_id', 1))
            ->where('answer_type', $request->input('answer_type'))
            ->where('difficulty_level', $request->input('difficulty_level', 'medium'))
            ->inRandomOrder()
            ->get();

        $randomizedQuestions = DescriptiveQuestionService::getRandomQuestions($questions);

        return response()->json($randomizedQuestions,);

    }

    public static function delete(DeleteDescriptiveRequest $request)
    {
        try {
            DB::beginTransaction();
            DescriptiveQuestions::destroy($request->question);
            DB::commit();
            return response(['message' => 'سوال مورد نظر با موفقیت حذف شد'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'حذف سوال با شکست مواجه شد'], 500);
        }
    }
    public function update(updateDescriptiveRequest $request)
    {

        try {
            DB::beginTransaction();
            $questionId = $request->question;
            $question = DescriptiveQuestions::findOrFail($questionId);
            $questionDTO = new DescriptivQuestionDTO($request->validated());
            $question->update((array) $questionDTO);
            DB::commit();
            return response()->json(['message' => 'سوال با موفقیت بروزرسانی شد'], 201);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

}
