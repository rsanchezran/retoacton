<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodigosPostalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigos_postales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('cp',7);
            $table->string('colonia',200);
            $table->string('estado',200);
            $table->string('ciudad',200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigos_postales');
    }
}
