<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsosProductos extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_Insumos',
        'fk_Actividades',
        'cantidadProducto',
    ];
}
