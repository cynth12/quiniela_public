<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jornada extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'fecha',
        'premio',
        'cerrada',
    ];

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'numero', 'numero')
                    ->orderBy('partido_numero'); // ✅ ordenados por número visible
    }

    // Relación hacia resultados oficiales
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'numero', 'numero');
    }

}
