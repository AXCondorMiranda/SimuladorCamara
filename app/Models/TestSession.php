<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'test_id', 'session_id'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respuestasUsuarios()
    {
        return $this->hasMany(RespuestaUsuario::class);
    }
}
