<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSituacionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('situacion_actual')->nullable();
            $table->boolean('situacion_actual_publico')->default(0);
            $table->string('genero_2')->nullable();
            $table->string('gym_2')->nullable();
            $table->string('gym_ciudad')->nullable();
            $table->string('numero')->nullable();
            $table->string('calle')->nullable();
            $table->string('numero_tarjeta')->nullable();
            $table->string('banco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
