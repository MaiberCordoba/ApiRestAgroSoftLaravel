<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controles extends Model
{
    use HasFactory;

    protected $table = 'controles';

    protected $fillable = [
        'fk_Afecciones',
        'fk_TiposControl',
        'descripcion',
        'fechaControl',
    ];

    protected $casts = [
        'fechaControl' => 'date',
    ];

    public function afeccion()
    {
        return $this->belongsTo(Afeccion::class, 'fk_Afecciones');
    }

    public function tipoControl()
    {
        return $this->belongsTo(TipoControl::class, 'fk_TiposControl');
    }
}