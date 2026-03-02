<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    protected $table = 'jugadors'; // nombre exacto de la tabla

    protected $fillable = ['nombre', 'telefono', 'pagada'];

    protected $casts = [ 'pagada' => 'boolean', ];

    public function quinielas()
{
    return $this->hasMany(Quiniela::class);
}

    
}
