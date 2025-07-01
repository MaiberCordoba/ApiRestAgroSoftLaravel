<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    protected $table = 'sensores';
    protected $fillable = [
        'tipo_sensor',
        'datos_sensor',
        'fecha',
        'era_id',
        'lote_id'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    // Relación con umbrales (1 sensor tiene muchos umbrales)
    public function umbrales(): HasMany
    {
        return $this->hasMany(Umbral::class);
    }

    public function getUnidadAttribute()
    {
        return match($this->tipo_sensor) {
            'Temperatura' => '°C',
            'Iluminación' => 'lux',
            'Humedad Ambiental', 'Humedad del Terreno' => '%',
            'Nivel de PH' => 'pH',
            'Viento' => 'km/h',
            'Lluvia' => 'mm',
            default => '',
        };
    }
}
