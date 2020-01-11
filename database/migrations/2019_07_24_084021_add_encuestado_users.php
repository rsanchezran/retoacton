<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEncuestadoUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('encuestado')->nullable();
            $table->boolean('cobrado')->nullable();
            $table->timestamp('fecha_inscripcion')->nullable();
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
            $table->dropColumn('encuestado');
            $table->dropColumn('cobrado');
            $table->dropColumn('fecha_inscripcion');
        });
    }
}
