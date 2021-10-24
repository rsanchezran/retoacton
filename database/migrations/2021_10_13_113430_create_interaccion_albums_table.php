<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteraccionAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interaccion_albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('dinero_acton')->default('');
            $table->string('tipo_like')->default('');
            $table->unsignedInteger('usuario_like_id');
            $table->foreign('usuario_like_id')->references('id')->on('users');
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')->references('id')->on('mi_albums');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interaccion_albums');
    }
}
