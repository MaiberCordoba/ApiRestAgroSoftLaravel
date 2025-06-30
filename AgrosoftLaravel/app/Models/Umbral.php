<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Umbral extends Model
{
    protected $table= 'umbrales';
    protected $fillable = [
        'sensor_id',
        'valor_minimo',
        'valor_maximo'
    ];

    // Relación con sensor (1 umbral pertenece a 1 sensor)
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}
