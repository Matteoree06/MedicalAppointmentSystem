<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;
    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
