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
        Schema::create('usosproductos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_Insumos');
            $table->unsignedBigInteger('fk_Actividades');
            $table->integer('cantidadProducto');
            $table->timestamps();

            $table->foreign('fk_Insumos')->references('id')->on('insumos')->onDelete('cascade');
            $table->foreign('fk_Actividades')->references('id')->on('actividades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usosproductos');
    }
};
