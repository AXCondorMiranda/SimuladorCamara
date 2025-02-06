<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateType extends Model
{
    use HasFactory;

    protected $table = 'affiliate_types';

    protected $fillable = ['name']; // Asegúrate de que el campo sea correcto
}
