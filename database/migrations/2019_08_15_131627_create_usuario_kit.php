<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioKit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_kit', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('kit_id')->index();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('kit_id')->references('id')->on('kits');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_kit');
    }
}
