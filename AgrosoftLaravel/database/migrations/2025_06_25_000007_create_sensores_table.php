<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensoresTable extends Migration
{
    public function up()
    {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_sensor', [
                'Temperatura',
                'Iluminación',
                'Humedad Ambiental',
                'Humedad del Terreno',
                'Nivel de PH',
                'Viento',
                'Lluvia'
            ]);
            $table->float('datos_sensor');
            $table->timestamp('fecha')->useCurrent();
            $table->foreignId('era_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('lote_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensores');
    }
}