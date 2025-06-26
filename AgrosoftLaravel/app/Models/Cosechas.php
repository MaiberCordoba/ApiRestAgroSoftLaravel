<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cosechas extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_Cultivos',
        'unidades',
        'fecha',
    ];
}
