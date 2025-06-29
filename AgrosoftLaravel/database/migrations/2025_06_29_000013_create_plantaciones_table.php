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
        Schema::create('plantaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_Eras')->references('id')->on('eras');
            $table->foreignId('fk_Cultivos')->references('id')->on('cultivos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantaciones');
    }
};
