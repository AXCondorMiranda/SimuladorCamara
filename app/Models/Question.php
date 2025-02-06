<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test;
use App\Models\Alternative;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'state'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }

    public function resultDetails()
    {
        return $this->hasMany(ResultDetail::class);
    }
    public function respuestasUsuarios()
    {
        return $this->hasMany(RespuestaUsuario::class);
    }
}
