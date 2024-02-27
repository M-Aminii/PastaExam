<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['textbook_id', 'name'];

    public function textbook()
    {
        return $this->belongsTo(Textbook::class);
    }
}
