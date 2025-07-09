<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plagas extends Model
{
    use HasFactory;

    protected $table = 'plagas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'img',
        'fk_TiposPlaga',
    ];

    public function tipoPlaga()
    {
        return $this->belongsTo(TiposPlaga::class, 'fk_TiposPlaga', 'id');
    }
}