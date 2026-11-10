<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado', function (Blueprint $table) {
            $table->integer('idEmpleado')->primary(); // Llave primaria manual (no autoincrementable)
            $table->string('nombre', 50);
            $table->string('apellidoP', 50);
            $table->string('apellidoM', 50)->nullable();
            $table->string('correo', 100);
            $table->string('contrasena', 255);
            $table->unsignedBigInteger('idRol')->nullable();

            $table->foreign('idRol')->references('idRol')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
