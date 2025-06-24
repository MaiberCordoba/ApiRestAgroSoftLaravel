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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('identificacion');
            $table->char('nombre', length:30);
            $table->char('apellidos', length:50);
            $table->date('fechaNacimiento');
            $table->char('telefono', length:15);
            $table->char('correoElectronico', length:200);
            $table->char('passwordHash', length:70);
            $table->enum('estado',['activo', 'inactivo']);
            $table->enum('rol',['admin', 'instructor', 'pasante', 'aprendiz', 'visitante']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
