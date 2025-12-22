<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    protected $fillable = [
        'numero', // jornada visible
        'partido_numero', // número dentro de la jornada
        'local',
        'visitante',
        'resultado_oficial',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'numero', 'numero');
    }

    public function resultado()
    {
        return $this->hasOne(Resultado::class, 'partido_numero', 'partido_numero')->where('numero', $this->numero);
    }
}
