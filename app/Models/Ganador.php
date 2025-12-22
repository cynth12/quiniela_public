<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ganador extends Model
{
    protected $table = 'ganadores';

    protected $fillable = [
        'numero',
        'quiniela_id',
        'jugador_id',
        'posicion',
        'aciertos'
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function quiniela()
    {
        return $this->belongsTo(Quiniela::class);
    }
}
