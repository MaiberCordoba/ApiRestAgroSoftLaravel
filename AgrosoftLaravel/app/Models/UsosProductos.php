<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsosProductos extends Model
{
    protected $table = 'usosproductos';
    use HasFactory;

    protected $fillable = [
        'fk_Insumos',
        'fk_Actividades',
        'cantidadProducto',
    ];
}
