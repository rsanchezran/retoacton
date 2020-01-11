<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEjercicios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ejercicio');
            $table->string('video');
            $table->tinyInteger('tipo');
            $table->boolean('genero');
            $table->boolean('objetivo');
            $table->boolean('lugar')->nullable();
            $table->unsignedInteger('dia_id');
            $table->foreign('dia_id')->references('id')->on('dias');
            $table->timestamps();
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
        Schema::dropIfExists('ejercicios');
    }
}
