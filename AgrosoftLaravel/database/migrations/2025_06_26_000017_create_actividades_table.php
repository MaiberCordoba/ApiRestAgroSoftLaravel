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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->integer('fk_Cultivos');
            $table->unsignedBigInteger('fk_Usuarios');
            $table->string('titulo');
            $table->text('descripcion');
            $table->date('fecha');
            $table->enum('estado', ['Asignada','Cancelada','Completada'])->default('Asignada');
            $table->timestamps();

            $table->foreign('fk_Cultivos')->references('id')->on('cultivos')->onDelete('cascade'); */
            $table->foreign('fk_Usuarios')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
