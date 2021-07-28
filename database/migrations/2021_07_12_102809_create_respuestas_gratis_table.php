<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestasGratisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_gratis', function(Blueprint $tabla){
            $tabla->increments('id');
            $tabla->unsignedInteger('pregunta_id')->index();
            $tabla->unsignedInteger('usuario_id')->nullable();
            $tabla->string('respuesta');
            $tabla->string('email');
            $tabla->timestamps();

            $tabla->foreign('pregunta_id')->references('id')->on('encuesta_entradas');
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
        Schema::dropIfExists('respuestas_gratis');
    }
}
