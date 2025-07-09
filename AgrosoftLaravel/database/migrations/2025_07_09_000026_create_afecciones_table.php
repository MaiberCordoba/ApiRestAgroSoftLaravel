<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('afecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_Plantaciones')->references('id')->on('plantaciones');
            $table->foreignId('fk_Plagas')->references('id')->on('plagas');
            $table->dateTime('fechaEncuentro');
            $table->enum('estado', ['SinTratamiento','EnControl','Eliminado'])->default('SinTratamiento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afecciones');
    }
};
