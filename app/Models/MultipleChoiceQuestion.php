<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleChoiceQuestion extends Model
{
    use HasFactory;

    const LEVEL_EASY = 'easy ';
    const LEVEL_MEDIUM = 'medium';
    const LEVEL_HARD = 'hard ';
    const LEVEL = [self::LEVEL_EASY,self::LEVEL_MEDIUM, self::LEVEL_HARD];

    const Direction_Right = 'Right to Left';
    const Direction_Left = 'Left to Right ';

    const Direction = [self::Direction_Right,self::Direction_Left];

    protected $fillable = [
        'user_id',
        'grade_level_id',
        'grade_id',
        'field_id',
        'textbook_id',
        'topic_id',
        'source',
        'direction',
        'difficulty_level',
        'question_text',
        'option1',
        'option2',
        'option3',
        'option4',
        'correct_option',
        'descriptive_answer',
        'created_at',
        'updated_at'
    ];

    public function book()
    {
        return $this->belongsTo(Textbook::class);
    }

    // ارتباط با مبحث
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }






}
