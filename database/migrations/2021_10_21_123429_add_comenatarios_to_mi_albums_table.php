<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComenatariosToMiAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mi_albums', function (Blueprint $table) {
            //
            $table->boolean('comentarios_publico')->default(1);
            $table->boolean('conteo_publico')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mi_albums', function (Blueprint $table) {
            //
        });
    }
}
