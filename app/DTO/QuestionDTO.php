<?php

namespace App\DTO;

class QuestionDTO
{
    public $user_id;
    public $grade_level_id;
    public $grade_id;
    public $field_id;
    public $textbook_id;
    public $topic_id;
    public $source;
    public $direction;
    public $difficulty_level;
    public $question_text;
    public $option1;
    public $option2;
    public $option3;
    public $option4;
    public $correct_option;
    public $descriptive_answer;



    public function __construct(array $data)
    {
        $this->user_id = auth('api')->id();
        $this->grade_level_id = $data['grade_level_id'];
        $this->grade_id = $data['grade_id'];
        $this->field_id = $data['field_id'];
        $this->textbook_id = $data['textbook_id'];
        $this->topic_id = $data['topic_id'];
        $this->source =$data['source'];
        $this->direction = $data['direction'];
        $this->difficulty_level = $data['difficulty_level'];
        $this->question_text = $data['question_text'];
        $this->option1 = $data['option1'];
        $this->option2 = $data['option2'];
        $this->option3 = $data['option3'];
        $this->option4 = $data['option4'];
        $this->correct_option = $data['correct_option'];
        $this->descriptive_answer = $data['descriptive_answer'];



    }
    public function convertCorrectOption()
    {
        $correctOption = 'option' . $this->correct_option;
        $this->correct_option = $this->$correctOption;
    }
}
