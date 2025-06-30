<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposDesecho extends Model
{
    protected $table = 'tiposdesecho';
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
