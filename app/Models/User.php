<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos asignables
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
        'perfil',       // AGREGADO — requerido por la mini tarea
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos
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
     * Accesor: calcular edad
     */
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento
            ? Carbon::parse($this->fecha_nacimiento)->diffInYears(Carbon::now())
            : null;
    }

    /**
     * Scope: usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Relación con citas
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
