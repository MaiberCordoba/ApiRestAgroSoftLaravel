<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especies extends Model
{
    use HasFactory;
     protected $table = 'especies';

    protected $fillable = [
        'nombre',
        'descripcion',
        'img',
        'tiempoCrecimiento',
        'fk_TiposEspecie',
    ];
}
