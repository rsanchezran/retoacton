<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAceptadoToRetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retos', function (Blueprint $table) {
            //
            $table->boolean('aceptado')->nullable(true);
            $table->boolean('aceptado_retador')->nullable(true);
            $table->dateTime('fecha_aceptado')->nullable(true);
            $table->dateTime('fecha_finalizado')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retos', function (Blueprint $table) {
            //
        });
    }
}
