<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semillero extends Model
{
    use HasFactory;
     protected $table = 'semilleros';

    protected $fillable = [
        'unidades',
        'fechaSiembra',
        'fechaEstimada',
        'fk_Especies',
    ];
}
