<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('controles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_Afecciones')->constrained('afecciones')->onDelete('restrict');
            $table->foreignId('fk_TiposControl')->constrained('tiposcontrol')->onDelete('restrict');
            $table->text('descripcion');
            $table->date('fechaControl');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controles');
    }
};