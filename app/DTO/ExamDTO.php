<?php

namespace App\DTO;

class ExamDTO
{
    public $user_id;
    public $grade_level_id;
    public $grade_id;
    public $field_id;
    public $textbook_id;
    public $topic_id;
    public $province_id;
    public $city_id;
    public $school_id;
    public $title;
    public $negative_mark;
    public $date_time;
    public $difficulty_level;
    public $duration_minutes;
    public $exam_type;
    public $year;
    public $month;



    public function __construct(array $data)
    {
        $this->user_id = auth('api')->id();
        $this->grade_level_id = $data['grade_level_id'];
        $this->grade_id = $data['grade_id'];
        $this->field_id = $data['field_id'];
        $this->textbook_id = $data['textbook_id'];
        $this->topic_id = $data['topic_id'];
        $this->province_id =$data['province_id'];
        $this->city_id = $data['city_id'];
        $this->school_id = $data['school_id'];
        $this->title = $data['title'];
        $this->negative_mark = $data['negative_mark'];
        $this->date_time = $data['date_time'];
        $this->difficulty_level = $data['difficulty_level'];
        $this->duration_minutes = $data['duration_minutes'];
        $this->exam_type = $data['exam_type'];
        $this->year = $data['year'];
        $this->month = $data['month'];



    }

}
