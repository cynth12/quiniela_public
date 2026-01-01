<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'jugador_id',
        'numero',
        'monto',
        'fecha_pago',
        'comprobante_pdf',
    ];

    // Relación con Jugador
    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    // Relación con Quinielas (opcional, si quieres listar todas las quinielas del jugador en esa jornada)
    public function quinielas()
    {
        return $this->hasMany(Quiniela::class, 'jugador_id', 'jugador_id')
                    ->whereColumn('numero', 'numero');
    }
}


