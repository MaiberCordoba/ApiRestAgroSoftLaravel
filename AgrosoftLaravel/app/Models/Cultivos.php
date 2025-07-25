<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivos extends Model
{

use HasFactory;
     protected $table = 'cultivos';

    protected $fillable = [
        'nombre',
        'unidades',
        'activo',
        'fechaSiembra',
        'fk_Especies',
    ];
}
