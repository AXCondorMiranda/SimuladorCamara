<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    use HasFactory;
    protected $table = 'test_sessions';

    protected $fillable = ['user_id', 'test_id','score', 'session_id'];

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respuestasUsuarios()
    {
        return $this->hasMany(RespuestaUsuario::class, 'test_session_id', 'session_id');
    }
}
