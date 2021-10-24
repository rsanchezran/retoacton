<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_coins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->double('monto');
            $table->unsignedInteger('usuario_id')->nullable(true);
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->string('tipo_compra');
            $table->string('referencia');
            $table->boolean('pagado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_coins');
    }
}
