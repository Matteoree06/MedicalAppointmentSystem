<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCita extends Model
{
    use HasFactory;

    protected $table = 'pagos_citas';

    protected $fillable = [
        'cita_id',
        'monto',
        'metodo_pago',
        'estado',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
