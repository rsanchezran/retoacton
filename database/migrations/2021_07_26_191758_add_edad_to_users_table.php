<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEdadToUsersTable extends Migration
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
            $table->unsignedInteger('edad')->nullable();
            $table->boolean('edad_publico')->nullable();
            $table->string('estudios')->nullable();
            $table->boolean('estudios_publico')->nullable();
            $table->string('gym')->nullable();
            $table->boolean('gym_publico')->nullable();
            $table->string('empleo')->nullable();
            $table->boolean('empleo_publico')->nullable();
            $table->string('intereses')->nullable();
            $table->boolean('intereses_publico')->nullable();
            $table->string('idiomas')->nullable();
            $table->boolean('idiomas_publico')->nullable();
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
