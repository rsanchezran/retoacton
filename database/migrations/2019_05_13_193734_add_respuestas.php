<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas', function(Blueprint $tabla){
            $tabla->increments('id');
            $tabla->unsignedInteger('pregunta_id')->index();
            $tabla->unsignedInteger('usuario_id')->index();
            $tabla->string('respuesta');
            $tabla->timestamps();

            $tabla->foreign('pregunta_id')->references('id')->on('preguntas');
            $tabla->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuestas');

    }
}
