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
        // Schema::create('revisores', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nombre');
        //     $table->string('apellido_paterno');
        //     $table->string('apellido_materno');
        //     $table->string('correo');
        //     $table->string('contrasena');
        //     $table->enum('rol', ['Revisor','Visualizador','Administrador']);
        //     $table->timestamps();
        // });
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
