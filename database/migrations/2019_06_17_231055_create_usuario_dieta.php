<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioDieta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_dieta', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('comida');
            $table->string('alimento');
            $table->unsignedInteger('usuario_id');
            $table->integer('dieta');
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
        Schema::dropIfExists('usuario_dieta');
    }
}
