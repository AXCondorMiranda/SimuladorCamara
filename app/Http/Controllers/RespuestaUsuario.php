<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaUsuario extends Model
{
    use HasFactory;

    protected $table = 'respuesta_usuarios'; // Asegúrate de que la tabla en la BD tiene este nombre
    protected $fillable = [
        'test_session_id',
        'test_id',
        'question_id',
        'respuesta',
        'es_correcta',
        'user_id'
    ];

    /**
     * Relación con la sesión de examen
     */
    public function testSession()
    {
        return $this->belongsTo(TestSession::class, 'test_session_id');
    }

    /**
     * Relación con la pregunta correspondiente
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Relación con el usuario que respondió
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
