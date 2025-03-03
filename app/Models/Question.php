<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RespuestaUsuario;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $primaryKey = 'id';
    // Usamos $guarded en lugar de $fillable para evitar problemas con la clave primaria
    protected $fillable = [
        'test_id',
        'description',
        'state'
    ];

    /**
     * Relación: Una pregunta puede tener múltiples respuestas de usuarios
     */
    public function respuestasUsuarios()
    {
        return $this->hasMany(RespuestaUsuario::class, 'question_id', 'id');
    }

    /**
     * Relación: Una pregunta pertenece a un test
     */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id', 'id');
    }

    /**
     * Relación: Una pregunta tiene muchas alternativas
     */
    public function alternatives()
    {
        return $this->hasMany(Alternative::class, 'question_id', 'id');
    }
    public function correctAnswer()
    {
        return $this->hasOne(Alternative::class, 'question_id', 'id')->where('is_correct', 1);
    }
    /**
     * Relación: Una pregunta puede estar en múltiples tests a través de la tabla `question_test`
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class, 'question_test', 'question_id', 'test_id');
    }
}
