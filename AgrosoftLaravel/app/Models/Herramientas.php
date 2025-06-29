<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Herramientas extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_Lotes',
        'nombre',
        'descripcion',
        'unidades'
    ];
}
