<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // La migracion 2021_02_17_044555 ya crea 'revisores' (la necesita por la
        // llave foranea de 'validaciones'). Aqui solo se ajustan los valores por defecto.
        if (Schema::hasTable('revisores')) {
            DB::statement("ALTER TABLE revisores
                MODIFY nombre VARCHAR(255) NOT NULL DEFAULT 'Revisor',
                MODIFY apellido_paterno VARCHAR(255) NOT NULL DEFAULT 'Designado',
                MODIFY apellido_materno VARCHAR(255) NOT NULL DEFAULT 'Comité',
                MODIFY rol ENUM('Revisor','Visualizador','Administrador') NOT NULL DEFAULT 'Revisor'");
            return;
        }

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
