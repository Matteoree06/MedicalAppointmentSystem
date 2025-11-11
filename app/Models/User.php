<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fecha_nacimiento',
        'sexo',
        'numero_seguro',
        'historial_medico',
        'contacto_emergencia',
        'telefono',
        'direccion',
        'tipo_sangre',
        'alergias',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'date',
            'activo' => 'boolean',
        ];
    }

    /**
     * Accessor para calcular la edad del paciente
     */
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->diffInYears(Carbon::now()) : null;
    }

    /**
     * Scope para obtener solo usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Relación con citas médicas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    /**
     * Relación con historial médico
     */
    public function historialMedicoRegistros()
    {
        return $this->hasMany(HistorialMedico::class, 'paciente_id');
    }
}
