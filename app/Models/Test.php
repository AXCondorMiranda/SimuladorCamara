<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_type_id',
        'name',
        'quantity',
        'is_practice',
        'state'
    ];

    public function test_type()
    {
        return $this->belongsTo(TestType::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }

    public function respuestasUsuarios()
    {
        return $this->hasMany(RespuestaUsuario::class);
    }
}
