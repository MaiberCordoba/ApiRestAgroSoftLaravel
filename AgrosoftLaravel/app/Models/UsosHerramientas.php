<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsosHerramientas extends Model
{
    protected $table = 'usosherramientas';
    use HasFactory;

    protected $fillable = [
        'fk_Herramientas',
        'fk_Actividades',
    ];
}
