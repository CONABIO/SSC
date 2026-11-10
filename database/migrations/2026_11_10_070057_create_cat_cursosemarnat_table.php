<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_cursosemarnat', function (Blueprint $table) {
            $table->increments('idCurso'); // int auto_increment primary key
            $table->string('nombre', 255);
            $table->integer('horas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_cursosemarnat');
    }
};
