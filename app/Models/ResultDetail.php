<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Result;

class ResultDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_answers',
        'question_id',
        'result_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}
