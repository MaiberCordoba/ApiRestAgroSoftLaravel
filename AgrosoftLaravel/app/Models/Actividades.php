<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_Cultivos',
        'fk_Usuarios',
        'titulo',
        'descripcion',
        'fecha',
        'estado',
    ];
}
