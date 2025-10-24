<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCita extends Model
{
    use HasFactory;

    protected $table = 'pagos_citas';

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
