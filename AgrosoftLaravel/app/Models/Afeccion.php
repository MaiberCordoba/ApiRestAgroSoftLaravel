<?php

namespace App\Models;

use App\Enums\EstadoAfeccion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afeccion extends Model
{
    use HasFactory;

    protected $table = 'afecciones';

    protected $fillable = [
        'fk_Plantaciones',
        'fk_Plagas',
        'fechaEncuentro',
        'estado',
    ];

    public function plantacion()
    {
        return $this->belongsTo(Plantaciones::class, 'fk_Plantaciones');
    }

    public function plaga()
    {
        return $this->belongsTo(Plagas::class, 'fk_Plagas');
    }
}