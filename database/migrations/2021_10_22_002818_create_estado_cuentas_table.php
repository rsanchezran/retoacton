<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoCuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_cuentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedInteger('usuario_transfiere_id')->nullable(true);
            $table->foreign('usuario_transfiere_id')->references('id')->on('users');
            $table->string('desripcion')->default('');
            $table->integer('coins')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estado_cuentas');
    }
}
