<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->default('Revisor');
            $table->string('apellido_paterno')->default('Designado');
            $table->string('apellido_materno')->default('Comité');
            $table->string('correo');
            $table->string('contrasena');
            $table->enum('rol', ['Revisor','Visualizador','Administrador'])->default('Revisor');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisores');
    }
}
