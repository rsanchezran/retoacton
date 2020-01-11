<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRangoDietas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rango_dietas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dieta_id');
            $table->unsignedInteger('rango_id');
            $table->string('tipo');
            $table->tinyInteger('comida');
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
        Schema::dropIfExists('rango_dietas');
    }
}
