<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function pagoCita()
    {
        return $this->hasOne(PagoCita::class);
    }
    protected $fillable = [
    'paciente_id',
    'medico_id',
    'consultorio_id',
    'fecha',
    'hora'
];

}
