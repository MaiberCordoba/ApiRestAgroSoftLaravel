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
        Schema::create('eras', function (Blueprint $table) {
            $table->id();
            $table->float('tamX');
            $table->float('tamY');
            $table->float('posX');
            $table->float('posY');
            $table->boolean('estado')->default(true);
            $table->foreignId('fk_Lotes')->references('id')->on('lotes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eras');
    }
};
