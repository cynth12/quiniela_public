<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'resultados';

    protected $fillable = [
        'numero',
        'partido_numero',
        'resultado_oficial',
    ];
    // Relaciones opcionales si decides usarlas
    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    

    /**
     * Scope para filtrar por jornada (numero)
     */
    public function scopeDeJornada($query, $numero)
    {
        return $query->where('numero', $numero);
    }

    /**
     * Scope para filtrar por partido dentro de la jornada
     */
    public function scopeDePartido($query, $partido_numero)
    {
        return $query->where('partido_numero', $partido_numero);
    }
}
