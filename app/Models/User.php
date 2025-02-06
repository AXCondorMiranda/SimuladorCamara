<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AffiliateType; // ✅ Agregado
use App\Models\RespuestaUsuario; // ✅ Agregado si hay error similar

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'affiliate_type_id', // Campo para la relación con AffiliateType
        'role',             // Asegúrate de incluir el campo "role" si es relevante
        'mobile_access',
        'web_access'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mutator for password hashing.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Relation with Result model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Relation with AffiliateType model.
     * Defines the connection between users and their affiliate type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliateType()
    {
        return $this->belongsTo(AffiliateType::class, 'affiliate_type_id');
    }

    /**
     * Scope for filtering users by role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for filtering users by affiliate type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $affiliateTypeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAffiliateType($query, $affiliateTypeId)
    {
        return $query->where('affiliate_type_id', $affiliateTypeId);
    }
    public function respuestasUsuarios()
    {
        return $this->hasMany(RespuestaUsuario::class);
    }

    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }
}
