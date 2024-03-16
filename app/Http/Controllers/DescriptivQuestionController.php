<?php

namespace App\Http\Controllers;

use App\DTO\DescriptivQuestionDTO;
use App\DTO\QuestionDTO;

use App\Http\Requests\DescriptivQuestion\CreateDescriptivQuestionRequest;
use App\Models\DescriptivQuestions;
use App\Models\Exam;
use App\Models\MultipleChoiceQuestion;
use App\Services\DescriptiveQuestionService;
use App\Services\MultipleChoiceQuestionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DescriptivQuestionController extends Controller
{

    public function create(CreateDescriptivQuestionRequest $request)
    {

        try {
            DB::beginTransaction();
            $questionDTO = new DescriptivQuestionDTO($request->validated());
            $question = DescriptivQuestions::create((array) $questionDTO);
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

        $questions = DescriptivQuestions::with(['book', 'topic'])
            ->where('textbook_id', $request->input('textbook_id', 1))
            ->where('topic_id', $request->input('topic_id', 1))
            ->where('answer_type', $request->input('answer_type'))
            ->where('difficulty_level', $request->input('difficulty_level', 'medium'))
            ->inRandomOrder()
            ->get();

        $randomizedQuestions = DescriptiveQuestionService::getRandomQuestions($questions);

        return response()->json($randomizedQuestions,);

    }

}
