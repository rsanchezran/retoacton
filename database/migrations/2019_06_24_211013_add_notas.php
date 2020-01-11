<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $tabla){
            $tabla->increments('id');
            $tabla->string('descripcion', 255);
            $tabla->boolean('objetivo')->nullable();
            $tabla->boolean('genero')->nullable();
            $tabla->unsignedInteger('dia_id');

            $tabla->foreign('dia_id')->references('id')->on('dias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
