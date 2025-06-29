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
        Schema::create('desechos', function (Blueprint $table) {
            $table->id();
            $table->integer('fk_Cultivos');
            $table->unsignedBigInteger('fk_TiposDesecho');
            $table->string('nombre');
            $table->text('descripcion');
            $table->timestamps();

            //$table->foreign('fk_Cultivos')->references('id')->on('cultivos')->onDelete('cascade');
            $table->foreign('fk_TiposDesecho')->references('id')->on('tiposdesecho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desechos');
    }
};
