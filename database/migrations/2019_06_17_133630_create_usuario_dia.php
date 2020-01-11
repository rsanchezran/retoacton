<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioDia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_dia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comentario', 255)->nullable();
            $table->unsignedInteger('dia_id');
            $table->unsignedInteger('usuario_id');
            $table->foreign('dia_id')->references('id')->on('dias');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_dia');
    }
}
