<?php

namespace App\Http\Controllers;

use App\DTO\QuestionDTO;
use App\Http\Requests\MultipleChoiceQuestion\CreateMultipleChoiceRequest;
use App\Http\Requests\MultipleChoiceQuestion\DeleteMultipleChoiceRequest;
use App\Http\Requests\MultipleChoiceQuestion\updateMultipleChoiceRequest;
use App\Models\Exam;
use App\Models\MultipleChoiceQuestion;
use App\Services\MultipleChoiceQuestionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MultipleChoiceQuestionController extends Controller
{

    public function create(CreateMultipleChoiceRequest $request)
    {

        try {
            DB::beginTransaction();
            $questionDTO = new QuestionDTO($request->validated());
            $questionDTO->convertCorrectOption();
            MultipleChoiceQuestion::create((array) $questionDTO);
            DB::commit();
            return response()->json(['message' => 'سوال با موفقیت اضافه شد'], 201);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }


    public static function listMultipleChoice(Request $request)
    {
        $questions = MultipleChoiceQuestion::with(['book', 'topic'])
            ->where('textbook_id', $request->input('textbook_id', 1))
            ->where('topic_id', $request->input('topic_id', 1))
            ->where('difficulty_level', $request->input('difficulty_level', 'medium'))
            ->inRandomOrder()
            ->get();


        $randomizedQuestions = MultipleChoiceQuestionService::getRandomQuestions($questions);

        return response()->json($randomizedQuestions);

    }

    public static function delete(DeleteMultipleChoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            MultipleChoiceQuestion::destroy($request->question);
            DB::commit();
            return response(['message' => 'سوال مورد نظر با موفقیت حذف شد'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'حذف سوال با شکست مواجه شد'], 500);
        }
    }

    public function update(updateMultipleChoiceRequest $request)
    {

        try {
            DB::beginTransaction();
            $questionId = $request->question;
            $question = MultipleChoiceQuestion::findOrFail($questionId);
            $questionDTO = new QuestionDTO($request->validated());
            $questionDTO->convertCorrectOption();
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
