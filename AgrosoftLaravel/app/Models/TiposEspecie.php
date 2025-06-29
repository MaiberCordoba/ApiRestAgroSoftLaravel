<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposEspecie extends Model
{
    use HasFactory;
     protected $table = 'tiposespecie';

    protected $fillable = [
        'nombre',
        'descripcion',
        'img',
    ];
}
