<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Test;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'questions_total',
        'duration',
        'total_marked',
        'total_correct',
        'total_incorrect',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function details()
{
    return $this->hasMany(ResultDetail::class);
}
    public function resultDetails()
    {
        return $this->hasMany(ResultDetail::class);
    }
    
}
