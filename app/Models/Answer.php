<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = ['question_id', 'texto', 'es_correcta'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
