<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuestas extends Model
{

    protected $table = 'respuestas';
    protected $fillable = [
        'quiniela_id',
        'partido_numero',
        'respuesta',
    ];
    
    public function quiniela()
    {
        return $this->belongsTo(Quiniela::class);
    }
}
