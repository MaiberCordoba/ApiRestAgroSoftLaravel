<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantaciones extends Model
{
    use HasFactory;

    protected $table = 'plantaciones';

    protected $fillable = [
        'fk_Eras',
        'fk_Cultivos',
    ];

    // Relación con el cultivo
    public function cultivos()
    {
        return $this->belongsTo(Cultivos::class, 'fk_Cultivos');
    }
 
}
