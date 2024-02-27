<?php

namespace App\Http\Controllers;

use App\DTO\QuestionDTO;
use App\Http\Requests\MultipleChoiceQuestion\CreateQuestionRequest;
use App\Models\Exam;
use App\Models\MultipleChoiceQuestion;
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
            ->inRandomOrder()
            ->get();

        $randomizedQuestions = [];
        foreach ($questions as $question) {
            $options = [
                $question->option1,
                $question->option2,
                $question->option3,
                $question->option4,
            ];
            shuffle($options);
            $correct_option_index = array_search($question->correct_option, $options);
            $randomizedQuestions[] = [
                'question_text' => $question->question_text,
                'options' => $options,
                'correct_option' => $correct_option_index + 1,
            ];
        }

        return response()->json($randomizedQuestions);

    }

}
