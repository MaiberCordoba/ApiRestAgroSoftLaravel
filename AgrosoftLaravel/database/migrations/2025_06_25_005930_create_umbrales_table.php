<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmbralesTable extends Migration
{
    public function up()
    {
        Schema::create('umbrales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained('sensores')->onDelete('cascade');
            $table->float('valor_minimo');
            $table->float('valor_maximo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('umbrales');
    }
}
