<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedInteger('usuario_retado_id');
            $table->foreign('usuario_retado_id')->references('id')->on('users');
            $table->unsignedInteger('usuario_reta_id');
            $table->foreign('usuario_reta_id')->references('id')->on('users');
            $table->boolean('publico')->default(1);
            $table->text('descripcion')->default('');
            $table->boolean('cumple')->default(1);
            $table->unsignedInteger('coins')->default(0);
            $table->string('video')->default('');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retos');
    }
}
