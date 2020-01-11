<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdenEjercicio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->dropColumn('etapa');
            $table->integer('orden');
            $table->unsignedInteger('serie_id')->nullable();
            $table->foreign('serie_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->dropForeign('ejercicios_serie_id_foreign');
            $table->dropColumn('serie_id');
            $table->dropColumn('orden');
            $table->string('etapa')->nullable();
        });
    }
}
