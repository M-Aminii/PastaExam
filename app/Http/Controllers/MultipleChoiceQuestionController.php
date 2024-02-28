<?php

namespace App\Http\Controllers;

use App\DTO\QuestionDTO;
use App\Http\Requests\MultipleChoiceQuestion\CreateQuestionRequest;
use App\Models\Exam;
use App\Models\MultipleChoiceQuestion;
use App\Services\MultipleChoiceQuestionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MultipleChoiceQuestionController extends Controller
{

    public function create(CreateQuestionRequest $request)
    {

        try {
            DB::beginTransaction();
            $questionDTO = new QuestionDTO($request->validated());
            $questionDTO->convertCorrectOption();
            $question = MultipleChoiceQuestion::create((array) $questionDTO);
            DB::commit();
            return response()->json($question, 201);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }


    public static function list(Request $request)
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

}
