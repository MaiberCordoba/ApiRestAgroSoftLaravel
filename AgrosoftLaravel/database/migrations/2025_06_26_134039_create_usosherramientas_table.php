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
        Schema::create('usosherramientas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_Herramientas');
            $table->unsignedBigInteger('fk_Actividades');
            $table->timestamps();

            $table->foreign('fk_Herramientas')->references('id')->on('herramientas')->onDelete('cascade');
            $table->foreign('fk_Actividades')->references('id')->on('actividades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usosherramientas');
    }
};
