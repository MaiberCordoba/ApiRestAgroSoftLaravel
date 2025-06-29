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
        Schema::create('semilleros', function (Blueprint $table) {
            $table->id();
            $table->integer('unidades');
            $table->date('fechaSiembra');
            $table->date('fechaEstimada');
            $table->foreignId('fk_Especies')->references('id')->on('especies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semilleros');
    }
};
