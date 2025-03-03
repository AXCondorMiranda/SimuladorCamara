<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class RespuestaUsuario extends Model
{
    use HasFactory;

    protected $table = 'respuesta_usuarios'; // Nombre exacto en la BD
    protected $primaryKey = 'id'; // Clave primaria
    public $timestamps = true;

    protected $fillable = [
        'test_session_id',
        'test_id',
        'question_id',
        'respuesta',
        'es_correcta',
        'user_id'
    ];

    protected $with = ['question'];

    // Asegurar que los datos se guardan en el formato correcto
    protected $casts = [
        'test_session_id' => 'string',
        'test_id' => 'integer',
        'question_id' => 'integer',
        'es_correcta' => 'boolean',
        'user_id' => 'integer'
    ];

    /**
     * Relación con la pregunta correspondiente
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
        

    /** 
     * Relación con la sesión del test
     */
    public function testSession()
    {
        return $this->belongsTo(TestSession::class, 'test_session_id', 'session_id');
    }


    /**
     * Relación con el usuario que respondió
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
