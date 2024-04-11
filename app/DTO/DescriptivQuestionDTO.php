<?php

namespace App\DTO;

use App\Models\DescriptiveQuestions;

class DescriptivQuestionDTO
{
    public $user_id;
    public $grade_level_id;
    public $grade_id;
    public $field_id;
    public $textbook_id;
    public $topic_id;
    public $source;
    public $answer_type;
    public $direction;
    public $difficulty_level;
    public $question_text;
    public $answer;
    public $explanation;



    public function __construct(array $data)
    {
        $this->user_id = auth('api')->id();
        $this->grade_level_id = $data['grade_level_id'];
        $this->grade_id = $data['grade_id'];
        $this->field_id = $data['field_id'];
        $this->textbook_id = $data['textbook_id'];
        $this->topic_id = $data['topic_id'];
        $this->source =$data['source'];
        $this->answer_type = $data['answer_type'];
        $this->direction = $data['direction'] ?? DescriptiveQuestions::Direction_Right;
        $this->difficulty_level = $data['difficulty_level'];
        $this->question_text = $data['question_text'];
        $this->answer = $data['answer'];
        $this->explanation = $data['explanation'];



    }

}
