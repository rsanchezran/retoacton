<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alimentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alimento');
            $table->string('porcion');
            $table->unsignedInteger('dia_id');
            $table->boolean('genero');
            $table->boolean('objetivo');
            $table->tinyInteger('comida');
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
        Schema::dropIfExists('alimentos');

    }
}
