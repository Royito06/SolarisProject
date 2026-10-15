<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidacionesTable extends Migration
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
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('correo');
            $table->string('contrasena');
            $table->enum('rol', ['Revisor','Visualizador','Administrador']);
            $table->rememberToken();
            $table->timestamps();
        });
        
        Schema::create('validaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_documento');
            $table->unsignedBigInteger('id_revisor')->nullable();
            $table->text('comentario')->nullable();
            $table->string('doc_aprobado')->nullable();
            $table->string('doc_rechazado')->nullable();
            $table->enum('estado',['Espera','Rechazado','Aprobado']);
            $table->string('fecha_validacion')->nullable();
            $table->timestamps();
            $table->foreign('id_documento')->references('id')->on('documentos');
            $table->foreign('id_revisor')->references('id')->on('revisores');
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
        Schema::dropIfExists('validaciones');
    }
}
