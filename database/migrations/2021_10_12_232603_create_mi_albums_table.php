<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mi_albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('archivo')->default('');
            $table->string('descripcion')->default('');
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
        Schema::dropIfExists('mi_albums');
    }
}
