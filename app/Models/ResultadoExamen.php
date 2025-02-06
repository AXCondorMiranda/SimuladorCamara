<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoExamen extends Model
{
    use HasFactory;

    protected $table = 'resultado_examen'; // Nombre de la tabla

    protected $fillable = [
        'user_id',
        'test_id',
        'question_id',
        'respuesta',
        'es_correcta',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
