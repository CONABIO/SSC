<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->increments('id'); // int auto_increment primary key
            $table->unsignedInteger('idEmpleado');
            $table->string('nombre', 255);
            $table->boolean('obligatorio')->default(0)->nullable();
            $table->boolean('validadoSemarnat')->default(0)->nullable();
            $table->integer('horas')->nullable();
            $table->decimal('calificacion', 4, 2)->nullable();
            $table->string('evidencia', 255)->nullable();
            $table->integer('periodo')->nullable();
            $table->string('validacion', 255)->nullable();

            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
