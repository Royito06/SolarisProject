<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->string('curp');
            //$table->integer('folio');
            $table->string('nombre', 80);
            $table->string('apellido_paterno', 80)->nullable();
            $table->string('apellido_materno', 80)->nullable();
            //$table->string('escuela_origen');
            $table->string('correo_electronico', 80)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('contrasena', 100);
            //$table->string('email');
            //$table->date('fecha_nacimiento')->nullable();
            //$table->integer('telefono');
            //$table->timestamp('fecha_registro')->nullable();
            $table->rememberToken();
            $table->timestamps();
/*             $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps(); */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
