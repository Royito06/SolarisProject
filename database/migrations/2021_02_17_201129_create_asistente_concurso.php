<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenteConcurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistente_concurso', function (Blueprint $table) {
            $table->unsignedBigInteger('id_concurso');
            $table->unsignedBigInteger('id_asistente');
            $table->foreign('id_concurso')->references('id')->on('concursos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_asistente')->references('id')->on('asistentes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistente_concurso');
    }
}
