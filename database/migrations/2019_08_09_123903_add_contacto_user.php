<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactoUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->renameColumn('nombre', 'nombres');

            $table->boolean('objetivo')->nullable();
            $table->string('apellidos')->nullable();

        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contactos', function (Blueprint $table){
            $table->renameColumn('nombres', 'nombre');

            $table->dropColumn('objetivo');
            $table->dropColumn('apellidos');
        });

        Schema::table('user', function (Blueprint $table){
            $table->dropColumn('last_name');
        });

    }
}
