<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test;

class TestType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'state'
    ];

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

}
