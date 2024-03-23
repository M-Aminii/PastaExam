<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    const LEVEL_EASY = 'easy ';
    const LEVEL_MEDIUM = 'medium';
    const LEVEL_HARD = 'hard ';
    const LEVEL = [self::LEVEL_EASY,self::LEVEL_MEDIUM, self::LEVEL_HARD];

    protected $fillable =
        [
        'user_id',
        'title',
        'grade_level_id',
        'grade_id',
        'field_id',
        'textbook_id',
        'topic_id',
        'province_id',
        'city_id',
        'school_id',
        'negative_mark',
        'date_time',
        'difficulty_level',
        'duration_minutes',
        'exam_type',
        'year',
        'month',
        ];

    public function getRelatedData() {
        return [
            'grade_level_id' => $this->grade_level_id,
            'grade_id' => $this->grade_id,
            'field_id' => $this->field_id,
            'textbook_id' => $this->textbook_id,
            'topic_id' => $this->topic_id,
        ];
    }


    public function questions()
    {
        return $this->belongsToMany(MultipleChoiceQuestion::class, 'exam_questions', 'exam_id', 'questions_data');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestions::class, 'exam_id');
    }


}
