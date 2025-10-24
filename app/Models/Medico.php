<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function historialMedico()
    {
        return $this->hasMany(HistorialMedico::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }
}
