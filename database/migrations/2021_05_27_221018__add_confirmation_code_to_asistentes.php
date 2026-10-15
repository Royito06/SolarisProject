<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmationCodeToAsistentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asistentes', function (Blueprint $table) {
            $table->string('confirmation_code')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('token_created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asistentes', function (Blueprint $table) {
            $table->dropColumn('confirmation_code');
        });
    }
}
