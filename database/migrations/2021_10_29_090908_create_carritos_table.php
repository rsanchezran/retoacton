<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carritos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('producto');
            $table->smallInteger('cantidad');
            $table->smallInteger('precio');
            $table->smallInteger('comision');
            $table->boolean('pagado')->default(0);
            $table->smallInteger('enviado')->default(0);
            $table->string('guia')->nullable();
            $table->string('servicio')->nullable();
            $table->string('comentarios')->nullable();
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carritos');
    }
}
