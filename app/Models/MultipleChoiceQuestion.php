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

    const Direction_Right = 'RTL';
    const Direction_Left = 'LTR';

    const Direction = [self::Direction_Right,self::Direction_Left];

    const Two_Options = 'TwoOptions';
    const Four_Options = 'FourOptions';
    const Five_Options = 'FiveOptions';

    const Type = [self::Two_Options,self::Four_Options,self::Five_Options];

    protected $fillable = [
        'user_id',
        'grade_level_id',
        'grade_id',
        'field_id',
        'textbook_id',
        'topic_id',
        'source',
        'question_type',
        'direction',
        'difficulty_level',
        'question_text',
        'option1',
        'option2',
        'option3',
        'option4',
        'option5',
        'correct_option',
        'explanation',
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

    public function isCreatedBy(User $user)
    {
        return $this->user_id === $user->id;
    }






}
