<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiniela extends Model
{
    protected $fillable = ['jugador_id', 'numero', 'numero_quiniela', 'pagada'];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuestas::class);
    }

    protected static function boot()
{
    parent::boot();

    static::deleting(function ($quiniela) {
        $quiniela->respuestas()->delete();
    });
}
}


